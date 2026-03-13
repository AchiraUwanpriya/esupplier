<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: ../admin.php');
    exit();
}

require_once __DIR__ . '/common/config.php';
include_once __DIR__ . '/queries/monthlytenderview_queries.php';

$entry = $_SESSION['entry'];

// Determine which button was clicked and set the mtd_type value accordingly
$mtd_type = 'PI'; // Default value for Monthly Tenders
$current_button = 'PVC Items';

if (isset($_GET['type'])) {
    switch ($_GET['type']) {       
        case 'pvc':
            $mtd_type = 'PI';
            $current_button = 'PVC Items';
            break;
        case 'medicine':
            $mtd_type = 'MI';
            $current_button = 'Medicine Items';
            break;
        case 'cables':
            $mtd_type = 'CB';
            $current_button = 'Cables';
            break;
    }
}

// Variables for view filters
$relevant_categories = getRelevantCategories($mtd_type);
$current_cat = $_GET['category'] ?? ($relevant_categories[0] ?? null);

// Function to get category name based on code
function getCategoryName($cat_code) {
    $category_names = [
        'P' => 'PVC Items',
        'I' => 'Medicine Items',
        'B' => 'Cables'
    ];
    return $category_names[$cat_code] ?? 'Unknown Category';
}

// Function to get relevant categories based on tender type
function getRelevantCategories($mtd_type) {
    switch($mtd_type) {
        case 'PI': return ['P'];
        case 'MI': return ['I'];
        case 'CB': return ['B'];
        default: return ['P'];
    }
}

$supcodevalue = null; // Defined for compatibility
$tenders = [];

$tsql = get_monthly_tenders_sql($mtd_type);
$stmt = mysqli_query($con, $tsql);

if ($stmt !== false) {
    // Optimization: Fetch ALL relevant catalogue items for this tender type ONCE
    $catalogue = [];
    $relevant_cats = getRelevantCategories($mtd_type);
    if (!empty($relevant_cats)) {
        $cat_list = "'" . implode("','", $relevant_cats) . "'";
        $cat_sql = "SELECT MMC_MATERIAL_CODE, MMC_DESCRIPTION, MMC_UNIT, MMC_CAT_CODE 
                    FROM mms_material_catalogue 
                    WHERE MMC_CAT_CODE IN ($cat_list)
                    ORDER BY MMC_DESCRIPTION ASC";
        $cat_stmt = mysqli_query($con, $cat_sql);
        while ($cat_row = mysqli_fetch_array($cat_stmt, MYSQLI_ASSOC)) {
            $catalogue[] = $cat_row;
        }
    }

    while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
        $tender = $row;
        // Sanitize tender number for HTML IDs
        $tender['id_safe'] = preg_replace('/[^A-Za-z0-9]/', '_', $row['mtd_tender_no']);
        
        $category_map = [
            'PI' => 'PVC Items',
            'MI' => 'Medicine Items', 
            'CB' => 'Cables'
        ];
        
        $category_name = $category_map[$mtd_type] ?? 'PVC Items';
        
        $tsql2 = get_monthly_tender_suppliers_sql($row['mtd_tender_no'], $category_name);
        $stmt2 = mysqli_query($con, $tsql2);
        
        if ($stmt2 === false) {
            continue; // Skip to next tender instead of dying
        }

        $suppliers = [];
        $supplier_codes = [];
        while ($row1 = mysqli_fetch_array($stmt2, MYSQLI_ASSOC)) {
            $supplier = $row1;
            $supplier['id_safe'] = preg_replace('/[^A-Za-z0-9]/', '_', $row1['msd_supplier_code']);
            
            // Initialize supplier items with FULL catalogue (restoring RIGHT JOIN behavior)
            $supplier_items = [];
            foreach ($catalogue as $cat_item) {
                $cat_code = $cat_item['MMC_CAT_CODE'];
                if (!isset($supplier_items[$cat_code])) {
                    $supplier_items[$cat_code] = [];
                }
                $supplier_items[$cat_code][$cat_item['MMC_MATERIAL_CODE']] = [
                    'MMC_DESCRIPTION' => $cat_item['MMC_DESCRIPTION'],
                    'MMC_UNIT' => $cat_item['MMC_UNIT'],
                    'mtt_price' => '',
                    'mtt_remark' => ''
                ];
            }
            $supplier['items'] = $supplier_items;
            $suppliers[$row1['msd_supplier_code']] = $supplier;
            $supplier_codes[] = "'" . mysqli_real_escape_string($con, $row1['msd_supplier_code']) . "'";
        }

        if (!empty($supplier_codes)) {
            // Optimization: Fetch ALL prices for THIS tender and THESE suppliers in ONE query
            $tsql3 = "SELECT t.mtt_supplier_code, t.mtt_material_code, t.mtt_remark, t.mtt_price
                      FROM mms_tenderprice_transactions t
                      WHERE t.mtt_tender_no = '" . mysqli_real_escape_string($con, $row['mtd_tender_no']) . "'
                      AND t.mtt_supplier_code IN (" . implode(',', $supplier_codes) . ")";
                      
            $stmt3 = mysqli_query($con, $tsql3);
            if ($stmt3 !== false) {
                while ($price_row = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
                    $sc = $price_row['mtt_supplier_code'];
                    $mc = $price_row['mtt_material_code'];
                    if (isset($suppliers[$sc])) {
                        foreach ($suppliers[$sc]['items'] as $cat_code => &$cat_data) {
                            if (isset($cat_data[$mc])) {
                                $cat_data[$mc]['mtt_price'] = $price_row['mtt_price'];
                                $cat_data[$mc]['mtt_remark'] = $price_row['mtt_remark'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        foreach ($suppliers as &$sup) {
            foreach ($sup['items'] as $cat_code => &$cat_data) {
                $cat_data = array_values($cat_data);
            }
        }
        
        $tender['suppilers'] = array_values($suppliers);
        $tenders[] = $tender;
    }
} else {
    echo "Error in query";
    die(print_r(mysqli_error($con), true));
}

// Added the renderer directly to the PHP logic block
function renderItem($items, $cat)
{
    if ($items && isset($items[$cat]) && !empty($items[$cat])) {
        echo '<table id="' . $cat . '" class="table table-hover table-bordered border-primary">';
        echo '<thead>';
        echo '<tr class="fixed">';
        echo '<th class="bg-info"><h3 class="fw-bold text-center">Description</h3></th>';
        echo '<th class="bg-info"><h3 class="fw-bold text-center">Unit</h3></th>';
        echo '<th class="bg-info"><h3 class="fw-bold text-center">Remarks</h3></th>';
        echo '<th class="bg-info"><h3 class="fw-bold text-center">Price (Rs.)</h3></th>';
        echo '</tr>';
        echo '</thead>';

        foreach ($items[$cat] as $obj) {
            echo '<tr>';
            echo '<td class="col-4">' . htmlspecialchars($obj['MMC_DESCRIPTION']) . '</td>';
            echo '<td class="col-2 text-center">' . htmlspecialchars($obj['MMC_UNIT']) . '</td>';
            echo '<td class="col-3 text-center">' . htmlspecialchars($obj['mtt_remark']) . '</td>';
            echo '<td class="col-3 text-center">' . htmlspecialchars($obj['mtt_price']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "<div class='alert alert-info text-center'>No Available Data</div>";
    }
}
?>
