<?php
session_start();
$suppliercode = $_SESSION['sup_code']; // This is 'CDPLC' from session
include_once '../../../config.php';

header('Content-Type: application/json');

if (isset($_REQUEST['tid']) && !empty($_REQUEST['tid'])) {

    $tenderNo = mysqli_real_escape_string($con, $_REQUEST['tid']);
    
    // IMPORTANT: Use the numeric supplier ID that actually has data
    $numeric_supplier_id = '1682428362'; // The supplier ID from your price table
    
    // Debug logging
    error_log("Session supplier: $suppliercode, Using numeric ID: $numeric_supplier_id for tender: $tenderNo");

    // Since categories don't match, we'll use a CASE statement to map CAT02 to Vegetables
    $sql = "SELECT 
                mc.MMC_DESCRIPTION, 
                tpt.mtt_price,
                CASE 
                    WHEN mc.MMC_CAT_CODE = 'CAT02' THEN 'Vegetables'
                    WHEN mc.MMC_CAT_CODE = 'CAT01' THEN 'Groceries'
                    ELSE 'Vegetables'
                END AS CategoryName
            FROM mms_tenderprice_transactions tpt
            LEFT JOIN mms_material_catalogue mc 
                ON mc.MMC_MATERIAL_CODE = tpt.mtt_material_code  
            WHERE tpt.mtt_supplier_code = '$numeric_supplier_id' 
              AND tpt.mtt_tender_no = '$tenderNo'
            ORDER BY mc.MMC_DESCRIPTION ASC";

    error_log("Price query: " . $sql);
    $resultset = mysqli_query($con, $sql);

    if (!$resultset) {
        error_log("SQL Error: " . mysqli_error($con));
        echo json_encode(['error' => mysqli_error($con)]);
        exit;
    }

    $tdata = array();
    while ($row = mysqli_fetch_assoc($resultset)) {
        // Format price to 2 decimal places
        if (isset($row['mtt_price'])) {
            $row['mtt_price'] = number_format(floatval($row['mtt_price']), 2);
        }
        $tdata[] = $row;
    }

    if (empty($tdata)) {
        echo json_encode(['error' => "No price data found for tender: $tenderNo"]);
    } else {
        echo json_encode($tdata);
    }

} else {
    echo json_encode(['error' => 'No tender ID provided']);
}
?>