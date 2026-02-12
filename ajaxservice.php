<?php

include 'config.php';

$func = isset($_REQUEST['func']) ? $_REQUEST['func'] : "";
switch (strtolower($func)) {
    case "changesuppstatus":
        changesSupplierStatus();
        break;
    case "selectsupplier":
        selectsupplier();
        break;
    case "confirmsupplier":
        confirmsupplier();
        break;
    case "bankstatusapprove":
        bankstatusapprove();
        break;
    default:
        returnDefault();
        break;
}


function returnDefault()
{
    $returnJson['message'] = "Not Found any Match";
    echo json_encode($returnJson);
}

function runquery($query)
{
    global $con;
    // $query_run = sqlsrv_query($con,$query); 
    $query_run = mysqli_query($con, $query);



    if ($query_run) {

        echo "Data Inserted Successfully!";
    } else {
        //   print_r(sqlsrv_errors());
        print_r(mysqli_error($con));
    }
}

function selectquery($query)
{
    global $con;
    //$con;
    // $query_run = sqlsrv_query($con,$query); 
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $datalist = [];
        while ($row = mysqli_fetch_array($query_run, MYSQLI_ASSOC)) {
            array_push($datalist, $row);
        }
        return $datalist;
    } else {
        //   print_r(sqlsrv_errors());
        print_r(mysqli_error($con));
    }
}

function changesSupplierStatus()
{
    try {
        global $suppliermobile;
        $suppliercode = $_REQUEST['suppliercode'];
        $suppliername = $_REQUEST['msd_supplier_name'];
        $supplieremail = $_REQUEST['msd_email_address'];
        $suppliermobile = $_REQUEST['msd_mobileno'];
        $supcat = $_REQUEST['msd_supply_category'];
        $description = $_REQUEST['msd_supply_category_des'];
        $supaddress = $_REQUEST['msd_address'];
        $supplieraction = $_REQUEST['supplieraction'] == "true" ? true : false;

        $_SESSION['msd_mobileno'] = $suppliermobile;

        var_dump($suppliermobile);
        // die();

        $supAction = $supplieraction ? "A" : "I";
        $query = "UPDATE mms_supplier_pending_details SET msd_status='$supAction' WHERE msd_supplier_code='$suppliercode'";
        runquery($query);

        $suppliermobile = $_SESSION['msd_mobileno'];

        $ch = curl_init();
        // $msg = "Congratulations, Now You're an approved supplier for the CDPLC. Please login & submit your tender.";l_address
        $msg = "Congratulations, Now you are an Active supplier for the CDPLC. Please login & Update your profile to Authorization!";

        curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendMsg?mobileNo=$suppliermobile&msg=" . urlencode($msg) . "");
        // curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendMsg?mobileNo=$suppliermobile&msg=Congratulations");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            "postvar1=value1&postvar2=value2&postvar3=valu3"
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $_output = curl_exec($ch);
        // var_dump($_output);
        curl_close($ch);

        if ($supplieraction) {
            // $query = "INSERT INTO mms_suppliers_details (msd_supplier_code,msd_supplier_name,msd_email_address,msd_mobileno,msd_supply_category,msd_address,msd_status) VALUES ($suppliercode,'$suppliername', '$supplieremail','$suppliermobile','$supcat','$supaddress','$supAction')";
            $query = "INSERT INTO mms_suppliers_details (msd_supplier_code,msd_supplier_name,msd_email_address,msd_mobileno,msd_supply_category,msd_supply_category_des,msd_address,msd_status)
        VALUES ('$suppliercode','$suppliername', '$supplieremail','$suppliermobile','$supcat','$description','$supaddress','$supAction')";
            runquery($query);
            $query = "DELETE FROM mms_supplier_pending_details WHERE msd_supplier_code='$suppliercode'";
            runquery($query);
        } else {
            $query = "DELETE FROM mms_suppliers_details WHERE msd_supplier_code='$suppliercode'";
            runquery($query);
        }
        $returnJson['message'] = "Successfull event for ";
        //echo json_encode($returnJson);
    } catch (Exception $ex) {
        returnDefault();
    }
}

function selectsupplier()
{
    try {
        $suppliercode = $_REQUEST['suppliercode'];

        $query = "SELECT * FROM mms_suppliers_details WHERE msd_supplier_code='$suppliercode'";
        $returnJson['message'] = "Successfull event for ";
        $datalist = selectquery($query);
        $returnJson['data'] = count($datalist) === 1 ? $datalist[0] : null;
        echo json_encode($returnJson);
    } catch (Exception $ex) {
        returnDefault();
    }
}

function confirmsupplier()
{
    try {
        $suppliercode = $_REQUEST['suppliercode'];
        $suppliermobile = $_REQUEST['supmobile'];

        $query = "UPDATE mms_suppliers_details SET msd_status='C' WHERE msd_supplier_code='$suppliercode'";
        $returnJson['message'] = "Successfull Updated ";
        runquery($query);

        $ch = curl_init();
        // $msg = "Congratulations, Now You're an approved supplier for the CDPLC. Please login & submit your tender.";l_address
        $msg = "Congratulations, Now you are an Authorized supplier for the CDPLC. Please login & Submit Your Tenders!";

        curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendMsg?mobileNo=$suppliermobile&msg=" . urlencode($msg) . "");
        // curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendMsg?mobileNo=$suppliermobile&msg=Congratulations");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            "postvar1=value1&postvar2=value2&postvar3=valu3"
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $_output = curl_exec($ch);
        // var_dump($_output);
        curl_close($ch);


        echo json_encode($returnJson);
    } catch (Exception $ex) {
        returnDefault();
    }
}

function bankstatusapprove()
{
    try {
        $suppliercode = $_REQUEST['suppliercode'];

        $query = "UPDATE mms_supplier_banks SET MSB_BANK_STATEMENT='Approved' WHERE MSB_SUPPLIER_CODE='$suppliercode'";
        $returnJson['message'] = "Successfull Updated ";
        runquery($query);
        echo json_encode($returnJson);
    } catch (Exception $ex) {
        returnDefault();
    }
}
