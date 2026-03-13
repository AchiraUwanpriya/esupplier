<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: ../admin.php');
    exit();
}

require_once __DIR__ . '/common/config.php';
include_once __DIR__ . '/queries/tenderview_queries.php';

$entry = $_SESSION['entry'];

$supcodevalue = null; // Defined for compatibility
$tenders = [];

$tsql = get_tenders_sql();
$stmt = mysqli_query($con, $tsql);

if ($stmt === false) {
    echo "Error in query";
    die(print_r(mysqli_error($con), true));
}

// Optimization: Fetch the FULL catalogue for these categories once per page load (or per tender if preferred)
$catalogue = [];
$cat_list = "'V', 'S', 'F', 'D', 'O', 'Y', 'C', 'E', 'R', 'H', 'M', 'P', 'I', 'B'";
$cat_sql = "SELECT MMC_MATERIAL_CODE, MMC_DESCRIPTION, MMC_UNIT, MMC_CAT_CODE 
            FROM mms_material_catalogue 
            WHERE MMC_CAT_CODE IN ($cat_list)
            ORDER BY MMC_DESCRIPTION ASC";
$cat_stmt = mysqli_query($con, $cat_sql);
while ($cat_row = mysqli_fetch_array($cat_stmt, MYSQLI_ASSOC)) {
    $catalogue[] = $cat_row;
}

while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
    $tender = $row;
    
    // Sanitize tender number for HTML IDs
    $tender['id_safe'] = preg_replace('/[^A-Za-z0-9]/', '_', $row['mtd_tender_no']);
    
    $tsql2 = get_tender_suppliers_sql($row['mtd_tender_no']);
    $stmt2 = mysqli_query($con, $tsql2);
    
    if ($stmt2 === false) {
        echo "Error in query for suppliers";
        die(print_r(mysqli_error($con), true));
    }

    $suppliers = [];
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
            // Add catalogue info with default empty price/remark
            $supplier_items[$cat_code][$cat_item['MMC_MATERIAL_CODE']] = [
                'MMC_DESCRIPTION' => $cat_item['MMC_DESCRIPTION'],
                'MMC_UNIT' => $cat_item['MMC_UNIT'],
                'mtt_price' => '',
                'mtt_remark' => ''
            ];
        }
        $supplier['items'] = $supplier_items;
        $suppliers[$row1['msd_supplier_code']] = $supplier;
    }

    // Fetch all prices for this tender and update the initialized catalogue
    $tsql3 = "SELECT mtt_supplier_code, mtt_material_code, mtt_remark, mtt_price
              FROM mms_tenderprice_transactions
              WHERE mtt_tender_no = '" . mysqli_real_escape_string($con, $row['mtd_tender_no']) . "'";
              
    $stmt3 = mysqli_query($con, $tsql3);
    if ($stmt3 === false) {
        echo "Error in bulk price query";
        die(print_r(mysqli_error($con), true));
    }

    while ($price_row = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
        $sc = $price_row['mtt_supplier_code'];
        $mc = $price_row['mtt_material_code'];
        
        if (isset($suppliers[$sc])) {
            // Find which category this material belongs to
            foreach ($suppliers[$sc]['items'] as $cat_code => &$cat_data) {
                if (isset($cat_data[$mc])) {
                    $cat_data[$mc]['mtt_price'] = $price_row['mtt_price'];
                    $cat_data[$mc]['mtt_remark'] = $price_row['mtt_remark'];
                    break;
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
?>
