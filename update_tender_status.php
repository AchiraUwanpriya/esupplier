<?php
include 'config.php';
date_default_timezone_set("Asia/Colombo");
function runquery($query)
{
    global $con;
    $query_run = mysqli_query($con, $query);
    if ($query_run) {

        echo "Data Inserted Successfully!";
    } else {
        print_r(mysqli_error($con));
    }
}

function selectquery($query)
{
    global $con;
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $datalist = [];
        while ($row = mysqli_fetch_array($query_run, MYSQLI_ASSOC)) {
            array_push($datalist, $row);
        }
        return $datalist;
    } else {
        print_r(mysqli_error($con));
    }
}

function closeTender($tender)
{
    $sql = "INSERT INTO cronjob_mms_tenderprice_transaction (mtt_year, mtt_tender_no,mtt_supplier_code,mtt_material_code,mtt_price,mtt_status,created_by,created_date)  
    SELECT mtt_year, mtt_tender_no,mtt_supplier_code,mtt_material_code,mtt_price,mtt_status,created_by,created_date FROM mms_tenderprice_transactions WHERE mtt_year = '" . $tender['mtd_year'] . "' and mtt_tender_no = '" . $tender['mtd_tender_no'] . "'";
    runquery($sql);
    $sql = "UPDATE mms_tender_details SET mtd_status = 'I' WHERE mtd_year = '" . $tender['mtd_year'] . "' and mtd_tender_no = '" . $tender['mtd_tender_no'] . "'";
    runquery($sql);
}

$sql = 'select * from mms_tender_details where mtd_status = "A"';
$tenderDetails = selectquery($sql);
foreach ($tenderDetails as $tenderDetail) {
    $closedate = strtotime($tenderDetail["mtd_bidclose_date"]);
    $dateNow = strtotime(date("Y-m-d h:i:sa"));

    if ($closedate <= $dateNow) {
        closeTender($tenderDetail);
    }
}
