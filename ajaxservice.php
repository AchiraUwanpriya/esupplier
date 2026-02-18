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
        global $con;
        
        $suppliercode = isset($_REQUEST['suppliercode']) ? $_REQUEST['suppliercode'] : null;
        $suppliername = isset($_REQUEST['msd_supplier_name']) ? $_REQUEST['msd_supplier_name'] : null;
        $supplieremail = isset($_REQUEST['msd_email_address']) ? $_REQUEST['msd_email_address'] : null;
        $suppliermobile = isset($_REQUEST['msd_mobileno']) ? $_REQUEST['msd_mobileno'] : null;
        $supcat = isset($_REQUEST['msd_supply_category']) ? $_REQUEST['msd_supply_category'] : null;
        $description = isset($_REQUEST['msd_supply_category_des']) ? $_REQUEST['msd_supply_category_des'] : null;
        $supaddress = isset($_REQUEST['msd_address']) ? $_REQUEST['msd_address'] : null;
        $supplieraction = $_REQUEST['supplieraction'] == "true" ? true : false;

        // Validation
        if (!$suppliercode || !$suppliername || !$supplieremail || !$suppliermobile) {
            throw new Exception("Missing required supplier information");
        }

        $_SESSION['msd_mobileno'] = $suppliermobile;

        if ($supplieraction) {
            // Approve supplier: Move from pending to registered
            
            // Start transaction
            mysqli_begin_transaction($con);
            
            // Insert into registered suppliers
            $insert_query = "INSERT INTO mms_suppliers_details 
                (msd_supplier_code, msd_supplier_name, msd_email_address, msd_mobileno, 
                 msd_supply_category, msd_supply_category_des, msd_address, msd_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'A')";
            
            $stmt = mysqli_prepare($con, $insert_query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . mysqli_error($con));
            }
            
            mysqli_stmt_bind_param($stmt, "sssssss", $suppliercode, $suppliername, $supplieremail, 
                                  $suppliermobile, $supcat, $description, $supaddress);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Insert failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
            
            // Delete from pending suppliers
            $delete_query = "DELETE FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
            $stmt = mysqli_prepare($con, $delete_query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . mysqli_error($con));
            }
            
            mysqli_stmt_bind_param($stmt, "s", $suppliercode);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Delete failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
            
            // Commit transaction
            mysqli_commit($con);
            
            // Send SMS notification
            $msg = "Congratulations, Now you are an Active supplier for the CDPLC. Please login & Update your profile to Authorization!";
            sendSMS($suppliermobile, $msg);
            
            $returnJson['message'] = "Supplier approved successfully";
        } else {
            // Reject supplier: Remove from pending
            $delete_query = "DELETE FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
            $stmt = mysqli_prepare($con, $delete_query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . mysqli_error($con));
            }
            
            mysqli_stmt_bind_param($stmt, "s", $suppliercode);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Delete failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
            
            $returnJson['message'] = "Supplier rejected successfully";
        }
        
        echo json_encode($returnJson);
    } catch (Exception $ex) {
        http_response_code(400);
        $returnJson['error'] = $ex->getMessage();
        echo json_encode($returnJson);
    }
}

function sendSMS($mobileNo, $msg)
{
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendMsg?mobileNo=" . urlencode($mobileNo) . "&msg=" . urlencode($msg));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("SMS sending failed: " . $error);
        }
    } catch (Exception $ex) {
        error_log("SMS Exception: " . $ex->getMessage());
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
