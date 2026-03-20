<?php
// backend/auth_controller.php - Refactored
session_start();
error_reporting(E_ALL & ~E_NOTICE);

$supCategoryShort = isset($_SESSION['sup_category']) ? strtoupper(trim($_SESSION['sup_category'])) : '';
require_once __DIR__ . '/common/db.php';
require_once __DIR__ . '/common/newsletterslk.class.php';

class AuthController
{
    private $db;

    function __construct()
    {
        $this->db = Database::getInstance();
        $this->processMobileVerification();
    }

    function processMobileVerification()
    {
        if (!isset($_POST["action"])) return;

        switch ($_POST["action"]) {
            case "send_otp":
                $mobile_number = $_POST['mobile_number'] ?? '';
                $isMobileExists = $this->mobileNumberExists($mobile_number);
                
                if ($isMobileExists === 0) {
                    echo 'block';
                    exit();
                }
                if ($isMobileExists === -1) {
                    echo 'pending';
                    exit();
                }

                $_SESSION['mobile_number'] = $mobile_number;

                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendOTP?mobileNo=$mobile_number");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $server_output = curl_exec($ch);
                    $_SESSION['session_otp'] = str_replace('"', "", $server_output);
                    curl_close($ch);

                    require_once(__DIR__ . "/otpverify.php");
                    exit();
                } catch (Exception $e) {
                    die('Error: ' . $e->getMessage());
                }
                break;

            case "verify_otp":
                $otp = $_POST['otp'] ?? '';
                $status = $this->setSessionSupCode();

                if (isset($_SESSION['session_otp']) && $otp == $_SESSION['session_otp']) {
                    unset($_SESSION['session_otp']);
                    echo json_encode(array("type" => "success", "message" => "Your mobile number is verified!", "status"=>$status));
                } else {
                    echo json_encode(array("type" => "error", "message" => "Mobile number verification failed"));
                    http_response_code(400);
                    exit;
                }
                break;
        }
    }

    function mobileNumberExists($mobileNumber)
    {
        // isActive=1, isPending=-1, isnotExist=0
        $tsql = "SELECT msd_mobileno,msd_status FROM mms_suppliers_details WHERE msd_mobileno=?
                UNION SELECT msd_mobileno,msd_status FROM mms_supplier_pending_details WHERE msd_mobileno=?
                LIMIT 1";
        $stmt = mysqli_prepare($this->db, $tsql);
        mysqli_stmt_bind_param($stmt, "ss", $mobileNumber, $mobileNumber);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
        
        $msd_status = 0;
        if ($row && isset($row["msd_status"])) { 
            $msd_status = $row["msd_status"] === "I" ? -1 : 1;
        }
        return $msd_status;
    }

    function setSessionSupCode()
    {
        if (isset($_SESSION['mobile_number'])) {
            $sql = "SELECT msd_supplier_code, msd_supplier_name, msd_status, msd_supply_category
            FROM mms_suppliers_details
            WHERE msd_mobileno = ?";
            
            $stmt = mysqli_prepare($this->db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['mobile_number']);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            $supStatus = null;
            if ($row = mysqli_fetch_row($res)) {
                $supCode = $row[0];
                $supName = $row[1];
                $supStatus = $row[2];
                $supCategory = $row[3];

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
                    $supCategoryShort = $supCategory ? strtoupper(substr(trim($supCategory), 0, 2)) : '';
                }

                $_SESSION['sup_code'] = $supCode;
                $_SESSION['sup_name'] = $supName;
                $_SESSION['sup_status'] = $supStatus;
                $_SESSION['sup_category'] = $supCategoryShort;
                $_SESSION['sup_category_full'] = $supCategory;
            }
            return $supStatus;
        }
        return null;
    }
}
$controller = new AuthController();
?>
