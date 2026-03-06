<?php
session_start();
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // if (isset($_POST['type'])) {

    $suppliercode = $_SESSION['sup_code'];

    $select = "SELECT MMC_DESCRIPTION, mtt_price,MMC_CAT_CODE,(SELECT mtc_description FROM mms_tendermaterial_categories WHERE mtc_cat_code = MMC_CAT_CODE) AS CategoryName FROM mms_tenderprice_transactions
    LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no
    LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code
    and mms_tender_details.mtd_status = 'A' 
    WHERE mms_tenderprice_transactions.mtt_supplier_code = '$suppliercode' AND MMC_CAT_CODE IN ('V','S','Y','F','D','C','O','E','R','H','M','P','I','B') ORDER BY MMC_CAT_CODE ASC";
    //$stmt = sqlsrv_query($con, $select);

    // SELECT DISTINCT MMC_CAT_CODE,(SELECT mtc_description FROM mms_tendermaterial_categories WHERE mtc_cat_code = MMC_CAT_CODE) AS CATDESC FROM mms_tenderprice_transactions LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code WHERE mms_tenderprice_transactions.mtt_supplier_code = '1674101717';


    // $query = "the query here";

    $result = mysqli_query($con,$select);

    $rows = array();
    while($r = mysqli_fetch_array($result)) {
        $rows[] = $r;
    }
    echo json_encode($rows);

}
?>