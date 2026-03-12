


<?php
session_start();
require_once 'backend/common/config.php';

if (!isset($_SESSION['sup_code'])) {
    http_response_code(403);
    exit;
}

$supplier_code = $_SESSION['sup_code'];
$user_cat = $_SESSION['sup_category'] ?? '';
$cat_code = $_GET['cat_code'] ?? '';
if (!$cat_code) {
    echo json_encode([]);
    exit;
}

// Determine active tender based on user category
if ($user_cat === 'RI') {
    $tender_subquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1)";
} else {
    $tender_subquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_cat' LIMIT 1)";
}

$query = "SELECT MMC_DESCRIPTION, mtt_price 
          FROM mms_material_catalogue
          INNER JOIN mms_tenderprice_transactions 
              ON mtt_material_code = MMC_MATERIAL_CODE 
              AND mtt_supplier_code = '$supplier_code'
              AND mtt_tender_no = $tender_subquery
              AND mtt_status = 'A'
          WHERE MMC_CAT_CODE = '$cat_code' AND MMC_STATUS = 'A'
          ORDER BY MMC_DESCRIPTION ASC";

$result = mysqli_query($con, $query);
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}
echo json_encode($items);