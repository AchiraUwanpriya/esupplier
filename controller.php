<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
// require ('textlocal.class.php');
$supCategoryShort = isset($_SESSION['sup_category']) ? strtoupper(trim($_SESSION['sup_category'])) : '';
include 'newsletterslk.class.php';

class Controller
{

    function __construct()
    {
        $this->processMobileVerification();
    }

    function processMobileVerification()
    {

        switch ($_POST["action"]) {
            case "send_otp":

                $mobile_number = $_POST['mobile_number'];

                $isMobileExists = $this->mobileNumberExists($mobile_number);
                // echo 'isMobileExists:'.$isMobileExists;
                
                if ($isMobileExists===0) {
                    echo 'block';
                    exit();
                }
                if ($isMobileExists===-1) {
                    echo 'pending';
                    exit();
                }

                $number = array(
                    $mobile_number
                );
                // $sender = 'PHPPOT';
                $_SESSION['mobile_number'] = $mobile_number;

                try {

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendOTP?mobileNo=$mobile_number");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt(
                        $ch,
                        CURLOPT_POSTFIELDS,
                        "postvar1=value1&postvar2=value2&postvar3=value3"
                    );

                    //var_dump($mobile_number);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $server_output = curl_exec($ch);
                    $_SESSION['session_otp'] = str_replace('"', "", $server_output);
                    //var_dump($server_output);

                    curl_close($ch);

                    require_once("otpverify.php");
                    exit();
                    // die();
                } catch (Exception $e) {
                    die('Error: ' . $e->getMessage());
                }
                break;

            case "verify_otp":
                $otp = $_POST['otp'];
                $status = $this->setSessionSupCode();

                if ($otp == $_SESSION['session_otp']) {
                    unset($_SESSION['session_otp']);
                    echo json_encode(array("type" => "success", "message" => "Your mobile number is verified!", "status"=>$status));
                    // header('Location: new.php');
                } else {
                    echo json_encode(array("type" => "error", "message" => "Mobile number verification failed"));
                    http_response_code(400);
                    exit;
                }
                break;
        }
    }

    function mobileNumberExists($mobileNuber)
    {
        // isActive=1, isPending=-1, isnotExist=0
        include 'config.php';
        $tsql = "SELECT msd_mobileno,msd_status FROM mms_suppliers_details WHERE msd_mobileno=$mobileNuber
                UNION SELECT msd_mobileno,msd_status FROM mms_supplier_pending_details WHERE msd_mobileno=$mobileNuber 
                LIMIT 1";
        $stmt = mysqli_query($con, $tsql);
		$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
        $msd_status = 0;
		if($row && $row["msd_status"]){ 
            // $msd_status = $row["msd_status"]==="A"?1:-1;
            $msd_status = $row["msd_status"]==="I"?-1:1;
        }
        return $msd_status;
    }

    function setSessionSupCode()
    {
        if ($_SESSION['mobile_number']) {
            include 'config.php';
            $sql = "SELECT msd_supplier_code, msd_supplier_name, msd_status, msd_supply_category
            FROM mms_suppliers_details
            WHERE msd_mobileno =" . $_SESSION['mobile_number'];
            
            if ($stmt = mysqli_query($con, $sql)) {
                while ($row = mysqli_fetch_row($stmt)) {
                    $supCode = $row[0];
                    $supName = $row[1];
                    $supStatus = $row[2];
                    $supCategory = $row[3]; // This is the full category name like "Ration Items"
                }
                
                // Convert full category name to short code
                $supCategoryShort = '';
                if ($supCategory === "Ration Items") {
                    $supCategoryShort = "RI";
                } elseif ($supCategory === "Medicine Items") {                  
                    $supCategoryShort = "MI";
                } elseif ($supCategory === "Cables") {
                    $supCategoryShort = "CB";
                } elseif ($supCategory === "PVC Items") {
                    $supCategoryShort = "PI";
                } elseif ($supCategory === "Hardware Items") {
                    $supCategoryShort = "HW";
                } elseif ($supCategory === "Office Supplies") {
                    $supCategoryShort = "OS";
                } else {
                    // fallback: normalize first two letters (uppercased)
                    $supCategoryShort = $supCategory ? strtoupper(substr(trim($supCategory), 0, 2)) : '';
                }

                if ($supCode) {
                    $_SESSION['sup_code'] = $supCode;
                    $_SESSION['sup_name'] = $supName;
                    $_SESSION['sup_status'] = $supStatus;
                    $_SESSION['sup_category'] = $supCategoryShort;
                    
                    // Also store the full category name for display purposes
                    $_SESSION['sup_category_full'] = $supCategory;
                } else {
                    unset($_SESSION['sup_code']);
                    unset($_SESSION['sup_name']);
                    unset($_SESSION['sup_status']);
                    unset($_SESSION['sup_category']);
                    unset($_SESSION['sup_category_full']);
                }
                return $supStatus;
            }
            if ($stmt === false) {
                die("database error:" . mysqli_error($con));
            }

            // Make the first (and in this case, only) row of the result set available for reading.
            if (mysqli_fetch_row($stmt) === false) {
                die("database error:" . mysqli_error($con));
            }
        }
    }
}
$controller = new Controller();
?>