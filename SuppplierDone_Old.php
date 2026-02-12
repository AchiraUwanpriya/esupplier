<?php
session_start();
include 'config.php';
date_default_timezone_set('Asia/Colombo');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suppliercode = $_SESSION['sup_code'];
    $date_now = date('Y-m-d g:i A');


    // Query to check if mtt_price is null for the given tender and supplier
    $checkPriceQuery = "SELECT `mtt_price` FROM `mms_tenderprice_transactions` WHERE `mtt_tender_no` = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A') AND `mtt_supplier_code` = '$suppliercode'";

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

    $select = "SELECT * FROM mms_suptender_details WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A') AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A') AND msd_supplier_code = '$suppliercode'";

    $stmt = mysqli_query($con, $select);
    if ($stmt === false) {
        die("Database error: " . mysqli_error($con));
        return;
    }

    $rows = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
    $insertSup = "";

    if ($rows !== null) {
        $insertSup = "UPDATE mms_suptender_details SET updated_by='$suppliercode', updated_date='$date_now'  WHERE msd_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A') AND msd_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A') AND msd_supplier_code = '$suppliercode'";
    } else {
        $insertSup = "INSERT INTO mms_suptender_details(msd_year, msd_tender_no, msd_supplier_code, msd_status, created_by, created_date) VALUES ((SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A'), (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A'), '$suppliercode', 'A', '$suppliercode', '$date_now')";
    }

    $query_run = mysqli_query($con, $insertSup);

    if ($query_run) {
        echo "<script>alert('Successfully Registered!');</script>";
    } else {
        echo "<script>alert('Tender Updated!');</script>";
    }
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
