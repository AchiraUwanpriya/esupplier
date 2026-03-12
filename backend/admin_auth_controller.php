<?php
// backend/admin_auth_controller.php - Refactored from adminapi.php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
require_once __DIR__ . '/common/db.php';

class AdminAuthController
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
                $service_number = $_POST['service_number'] ?? '';
                $admobile = $this->adminMobileNumberExists($service_number);

                if ($admobile === 0) {
                    echo 'block';
                    exit();
                }

                $_SESSION['mobile_number'] = $admobile;

                try {
                    $mobile_number = $admobile;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://esystems.cdl.lk/apidock/api/SMS/SendOTP?mobileNo=$mobile_number");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $server_output = curl_exec($ch);
                    $_SESSION['session_otp'] = str_replace('"', "", $server_output);
                    curl_close($ch);

                    require_once(__DIR__ . "/adminotp_verify.php");
                    exit();
                } catch (Exception $e) {
                    die('Error: ' . $e->getMessage());
                }
                break;

            case "verify_otp":
                $otp = $_POST['otp'] ?? '';
                $status = $this->setSession();

                if (isset($_SESSION['session_otp']) && $otp == $_SESSION['session_otp']) {
                    unset($_SESSION['session_otp']);
                    echo json_encode(array("type" => "success", "message" => "Your service number is verified!", "status" => $status));
                } else {
                    echo json_encode(array("type" => "error", "message" => "Service number verification failed"));
                    http_response_code(400);
                    exit;
                }
                break;
        }
    }

    function adminMobileNumberExists($service_number)
    {
        $tsql1 = "SELECT mobile_number, admin_status FROM mms_admin_details WHERE service_number = ?";
        $stmt = mysqli_prepare($this->db, $tsql1);
        mysqli_stmt_bind_param($stmt, "s", $service_number);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

        if ($row && isset($row["mobile_number"])) {
            return $row["mobile_number"];
        } else {
            return 0;
        }
    }

    function setSession()
    {
        if (isset($_SESSION['mobile_number'])) {
            $sql = "SELECT `service_number`,`name`,`admin_status`,`entry` FROM `mms_admin_details` WHERE mobile_number = ?";
            $stmt = mysqli_prepare($this->db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['mobile_number']);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            $status = null;
            if ($row = mysqli_fetch_row($res)) {
                $serviceNum = $row[0];
                $name = $row[1];
                $status = $row[2];
                $entry = $row[3];

                $_SESSION['service_num'] = $serviceNum;
                $_SESSION['name'] = $name;
                $_SESSION['admin_status'] = $status;
                $_SESSION['entry'] = $entry;
            }
            return $status;
        }
        return null;
    }
}
$controller = new AdminAuthController();
?>
