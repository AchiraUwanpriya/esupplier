<?php
session_start();
$suppliercode = $_SESSION['sup_code'];
include_once("config.php");

if ($_REQUEST['tid']) {
    $sql = "SELECT MMC_DESCRIPTION, mtt_price,MMC_CAT_CODE,(SELECT mtc_description FROM mms_tendermaterial_categories 
    WHERE mtc_cat_code = MMC_CAT_CODE) AS CategoryName FROM mms_tenderprice_transactions 
    LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no 
    LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code  
    WHERE mms_tenderprice_transactions.mtt_supplier_code = '$suppliercode' AND mtt_tender_no = '".$_REQUEST['tid']."' ORDER BY MMC_CAT_CODE ASC;";
    
    // var_dump($sql);
    // die();
    // var_dump($_REQUEST['tid']);
    
    $resultset = mysqli_query($con, $sql);
    
    $_SESSION['tid'] = $_REQUEST['tid'];
    $tdata = array();
    while ($row = mysqli_fetch_assoc($resultset)) {
        $tdata[] = $row;
        // var_dump($tdata);
        // die();
    }
    echo json_encode($tdata);
} else {
    echo 0;
}
?>


<?php
// include_once("include/db_connect.php");
// if($_REQUEST['empid']) {
// 	$sql = "SELECT id, employee_name, employee_salary, employee_age 
// 	FROM employee WHERE id='".$_REQUEST['empid']."'";
// 	$resultSet = mysqli_query($conn, $sql);	
// 	$empData = array();
// 	while( $emp = mysqli_fetch_assoc($resultSet) ) {
// 		$empData = $emp;
// 	}
// 	echo json_encode($empData);
// } else {
// 	echo 0;	
// }
?>
