<?php
session_start();
$user_category = isset($_SESSION['sup_category']) ? $_SESSION['sup_category'] : '';
require_once 'backend/common/config.php';
date_default_timezone_set('Asia/Colombo');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suppliercode = $_SESSION['sup_code'];
    $date_now = date('Y-m-d g:i A');


    // Query to check if mtt_price is null for the given tender and supplier
    if($user_category === 'RI') {
        // For RI category, check for NULL type
        $checkPriceQuery = "SELECT `mtt_price` FROM `mms_tenderprice_transactions` WHERE `mtt_tender_no` = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL) AND `mtt_supplier_code` = '$suppliercode'";
    } else {
        $checkPriceQuery = "SELECT `mtt_price` FROM `mms_tenderprice_transactions` WHERE `mtt_tender_no` = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category') AND `mtt_supplier_code` = '$suppliercode'";
    }

           // $checkPriceQuery = "SELECT `mtt_price` FROM `mms_tenderprice_transactions` WHERE `mtt_tender_no` = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' ) AND `mtt_supplier_code` = '$suppliercode'";

    

    $checkPriceResult = mysqli_query($con, $checkPriceQuery);
    if ($checkPriceResult === false) {
        die("Database error: " . mysqli_error($con));
        return;
    }

    $priceRow = mysqli_fetch_array($checkPriceResult, MYSQLI_ASSOC);

    if ($priceRow === null || $priceRow['mtt_price'] === null) {
        echo "<script>alert('Please input and save item values before submitting the tender.');</script>";
        return;
    }
    $tenderNo = '';
    if($user_category === 'RI') {
        // For RI category, select tender with NULL type
        $select = "SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1";
    } else {
        $select = "SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category' LIMIT 1";
    }
    // $select = "SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' LIMIT 1";
    $stmt = mysqli_query($con, $select);
    if ($stmt === false) {
        die("Database error: " . mysqli_error($con));
        return;
    }
    while ($row = mysqli_fetch_assoc($stmt)) {
        $tenderNo = $row['mtd_tender_no'];
    }

    if($user_category === 'RI') {
    $select = "SELECT * FROM mms_suptender_details WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL) AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL) AND msd_supplier_code = '$suppliercode'";
    } else {
    $select = "SELECT * FROM mms_suptender_details WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category') AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category') AND msd_supplier_code = '$suppliercode'";
    }
     
    //$select = "SELECT * FROM mms_suptender_details WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A' ) AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' ) AND msd_supplier_code = '$suppliercode'";

    $stmt = mysqli_query($con, $select);
    if ($stmt === false) {
        die("Database error: " . mysqli_error($con));
        return;
    }
    $rows = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
    $insertSup = "";

    if ($rows !== null) {
            if($user_category === 'RI') {
                 $insertSup = "UPDATE mms_suptender_details SET updated_by='$suppliercode', updated_date='$date_now'  WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL) AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL) AND msd_supplier_code = '$suppliercode'";
            }else{
                 $insertSup = "UPDATE mms_suptender_details SET updated_by='$suppliercode', updated_date='$date_now'  WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category') AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category') AND msd_supplier_code = '$suppliercode'";
            }
       // $insertSup = "UPDATE mms_suptender_details SET updated_by='$suppliercode', updated_date='$date_now'  WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A') AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A') AND msd_supplier_code = '$suppliercode'";
    } else {
        if($user_category === 'RI') {
            $insertSup = "INSERT INTO mms_suptender_details(msd_year, msd_tender_no, msd_supplier_code, msd_status, created_by, created_date) VALUES ((SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL), (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL), '$suppliercode', 'A', '$suppliercode', '$date_now')";}
        else{
            $insertSup = "INSERT INTO mms_suptender_details(msd_year, msd_tender_no, msd_supplier_code, msd_status, created_by, created_date) VALUES ((SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category'), (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category'), '$suppliercode', 'A', '$suppliercode', '$date_now')";}
        }
       // $insertSup = "INSERT INTO mms_suptender_details(msd_year, msd_tender_no, msd_supplier_code, msd_status, created_by, created_date) VALUES ((SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A'), (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A'), '$suppliercode', 'A', '$suppliercode', '$date_now')";
    }

    $query_run = mysqli_query($con, $insertSup);

    if ($query_run) {
        echo "<script>alert('Successfully Registered!');</script>";
    } else {
        echo "<script>alert('Tender Updated!');</script>";
    }
    //updated
    //send message to supplier
    $select = "SELECT msd_mobileno FROM mms_suppliers_details WHERE msd_supplier_code = '$suppliercode'";

    $stmt = mysqli_query($con, $select);
    if ($stmt === false) {
        die("Database error: " . mysqli_error($con));
        return;
    }
    while ($row = mysqli_fetch_assoc($stmt)) {
        //sendMessage($row['msd_mobileno'], "$tenderNo submitted successfully", "Esupplier");
        $message = "$tenderNo Tender submitted successfully.";
        sendMessage($row['msd_mobileno'], $message, "Esupplier");
    }


function sendMessage($mobileNo = '', $msg = '', $subject = '')
{
    $ch = curl_init();
    $uri = "https://esystems.cdl.lk/apidock/api/SMS/SendMsgTxt?mobileNo=" . urlencode($mobileNo) . "&msg=" . urlencode($msg) . "&subject=" . urlencode($subject);
    echo ($uri);
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    curl_close($ch);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSupplier-CDPLC</title>
</head>

<body>

</body>

</html>