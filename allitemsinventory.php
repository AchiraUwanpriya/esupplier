<?php
session_start();
include 'config.php';

// Ensure supplier is logged in
if (!isset($_SESSION['sup_code'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$supplier_code = $_SESSION['sup_code'];
$user_category = $_SESSION['sup_category'] ?? '';

if (!$user_category) {
    echo json_encode([]);
    exit;
}

// Determine active tender subquery based on user category
if ($user_category === 'RI') {
    $tender_subquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1)";
} else {
    $tender_subquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category' LIMIT 1)";
}

// Get all cat_codes and their display names for this supplier category
$cat_query = "SELECT cat_code, display_name FROM mms_category_forms WHERE supplier_category = ?";
$stmt = mysqli_prepare($con, $cat_query);
mysqli_stmt_bind_param($stmt, 's', $user_category);
mysqli_stmt_execute($stmt);
$cat_result = mysqli_stmt_get_result($stmt);
$cat_codes = [];
$cat_names = [];
while ($row = mysqli_fetch_assoc($cat_result)) {
    $cat_codes[] = $row['cat_code'];
    $cat_names[$row['cat_code']] = $row['display_name'];
}
mysqli_stmt_close($stmt);

if (empty($cat_codes)) {
    echo json_encode([]);
    exit;
}

// Build a comma-separated list of cat_codes for the IN clause
$cat_list = "'" . implode("','", $cat_codes) . "'";

// Query all items belonging to these categories, including their tender price if any
$query = "SELECT 
            c.MMC_CAT_CODE,
            c.MMC_DESCRIPTION,
            t.mtt_price
          FROM mms_material_catalogue c
          LEFT JOIN mms_tenderprice_transactions t 
            ON t.mtt_material_code = c.MMC_MATERIAL_CODE 
            AND t.mtt_supplier_code = '$supplier_code'
            AND t.mtt_tender_no = $tender_subquery
          WHERE c.MMC_CAT_CODE IN ($cat_list) AND c.MMC_STATUS = 'A'
          ORDER BY c.MMC_CAT_CODE, c.MMC_DESCRIPTION";

$result = mysqli_query($con, $query);
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed']);
    exit;
}

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = [
        'CategoryName' => $cat_names[$row['MMC_CAT_CODE']] ?? $row['MMC_CAT_CODE'],
        'MMC_DESCRIPTION' => $row['MMC_DESCRIPTION'],
        'mtt_price' => $row['mtt_price'] // may be null if no price entered
    ];
}

header('Content-Type: application/json');
echo json_encode($items);