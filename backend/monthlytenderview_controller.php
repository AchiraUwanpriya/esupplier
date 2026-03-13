<?php
session_start();

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
    while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
        $tender = $row;
        
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

        $suppilers = [];
        while ($row1 = mysqli_fetch_array($stmt2, MYSQLI_ASSOC)) {
            $suppiler = $row1;
            $items = [];
            
            $categories_to_query = getRelevantCategories($mtd_type);

            foreach ($categories_to_query as $cat_code) {
                $tsql3 = get_monthly_tender_items_sql($row1['msd_supplier_code'], $row['mtd_tender_no'], $cat_code);
                $stmt3 = mysqli_query($con, $tsql3);
                
                if ($stmt3 !== false) {
                    $category_items = [];
                    while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
                        $category_items[] = $item;
                    }
                    $items[$cat_code] = $category_items;
                }
            }

            $suppiler['items'] = $items;
            $suppilers[] = $suppiler;
        }
        $tender['suppilers'] = $suppilers;
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
