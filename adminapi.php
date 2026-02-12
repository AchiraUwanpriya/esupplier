<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
// require ('textlocal.class.php');

// include 'newsletterslk.class.php';

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

                $mobile_number = "";
                // $mobile_number = $_POST['mobile_number'];

                $service_number = $_POST['service_number'];

                // $admin_mobile_number = $_POST['mobile_number'];

                // $isMobileExists = $this->adminMobileNumberExists($service_number);

                $admobile = $this->adminMobileNumberExists($service_number);

                if ($admobile === 0) {
                    echo 'block';
                    exit();
                }

                $number = array(
                    $admobile,
                );

                $_SESSION['mobile_number'] = $admobile;

                // var_dump($_SESSION['mobile_number']);
                // die();
                // $sender = 'PHPPOT';

                try {

                    $mobile_number = $admobile;

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

                    require_once("adminotp_verify.php");
                    exit();
                    // die();
                } catch (Exception $e) {
                    die('Error: ' . $e->getMessage());
                }
                break;

            case "verify_otp":
                $otp = $_POST['otp'];
                $status = $this->setSession();

                if ($otp == $_SESSION['session_otp']) {
                    unset($_SESSION['session_otp']);
                    echo json_encode(array("type" => "success", "message" => "Your service number is verified!", "status" => $status));
                    // header('Location: new.php');
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
        include 'config.php';
        $tsql1 = "SELECT mobile_number, admin_status FROM mms_admin_details WHERE service_number = '$service_number'";
        $stmt = mysqli_query($con, $tsql1);

        if (!$stmt) {
            // Handle the query error here (e.g., log, return an error code)
            return 0;
        }

        $row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

        if ($row && isset($row["mobile_number"])) {
            $mbnum = $row["mobile_number"];
            return $mbnum;
        } else {
            return 0;
        }
    }

    function setSession()
    {
        if ($_SESSION['mobile_number']) {
            include 'config.php';
            $sql = "SELECT `service_number`,`name`,`admin_status`,`entry` FROM `mms_admin_details` WHERE mobile_number =" . $_SESSION['mobile_number'];
            if ($stmt = mysqli_query($con, $sql)) {
                while ($row = mysqli_fetch_row($stmt)) {
                    $serviceNum = $row[0];
                    $name = $row[1];
                    $status = $row[2];
                    $entry = $row[3];

                    // var_dump($serviceNum);
                    // var_dump($name);
                    // var_dump($status);
                    // die();
                }

                if ($serviceNum) {
                    $_SESSION['service_num'] = $serviceNum;
                    $_SESSION['name'] = $name;
                    $_SESSION['admin_status'] = $status;
                    $_SESSION['entry'] = $entry;
                } else {
                    unset($_SESSION['service_num']);
                    unset($_SESSION['name']);
                    unset($_SESSION['admin_status']);
                    unset($_SESSION['entry']);
                }
                return $status;
            }
            if ($stmt === false) {
                // die( print_r( sqlsrv_errors(), true));
                die("database error:" . mysqli_error($con));
            }

            // Make the first (and in this case, only) row of the result set available for reading.
            if (mysqli_fetch_row($stmt) === false) {
                // die( print_r( sqlsrv_errors(), true));
                die("database error:" . mysqli_error($con));
            }

            // Get the row fields. Field indices start at 0 and must be retrieved in order.
            // Retrieving row fields by name is not supported by sqlsrv_get_field.



            // $supCode = sqlsrv_get_field( $stmt, 0);
            // $supName = sqlsrv_get_field( $stmt, 1);

            // var_dump($supName);
            // die();



        }
    }
}
$controller = new Controller();
