<?php
// backend/auth/supplier_auth.php
require_once __DIR__ . '/../common/db.php';
require_once __DIR__ . '/../supplier/supplier_queries.php';

session_start();

class SupplierAuth {
    private $queries;

    public function __construct() {
        $this->queries = new SupplierQueries();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $action = isset($_POST['action']) ? $_POST['action'] : '';

        switch ($action) {
            case 'login':
                $this->login();
                break;
            case 'register':
                $this->register();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    }

    private function login() {
        $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
        
        if (empty($mobile)) {
            echo json_encode(['success' => false, 'message' => 'Mobile number is required']);
            return;
        }

        $user = $this->queries->getSupplierByMobile($mobile);

        if ($user) {
            if ($user['msd_status'] === 'I') {
                echo json_encode(['success' => false, 'message' => 'User Approval Is Pending!!!']);
                return;
            }
            
            // Set all required session variables for dashboard.php
            $_SESSION["msd_supplier_name"] = $user['msd_supplier_name'];
            $_SESSION["msd_supplier_code"] = $user['msd_supplier_code'];
            $_SESSION['sup_code'] = $user['msd_supplier_code'];
            $_SESSION['sup_name'] = $user['msd_supplier_name'];
            $_SESSION['sup_status'] = $user['msd_status'];
            $_SESSION['mobile_number'] = $mobile;
            
            // Handle category short-code translation (mirroring controller.php)
            $supCategory = $user['msd_supply_category'];
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
            
            $_SESSION['sup_category'] = $supCategoryShort;
            $_SESSION['sup_category_full'] = $supCategory;

            echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
        } else {
            echo json_encode(['success' => false, 'message' => 'User Does Not Exists!!!']);
        }
    }

    private function register() {
        // Basic validation logic from supRegistration.php
        $requiredFields = ['supname', 'mobile', 'email', 'supcat', 'description', 'address'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                die('<p style="color:red;text-align:center;">Please fill all fields!</p>');
            }
        }

        if (empty($_POST['g-recaptcha-response'])) {
            die('<p style="color:red;text-align:center;">Captcha verification failed!</p>');
        }

        $mobile = $_POST['mobile'];
        if ($this->queries->checkMobileExists($mobile)) {
            die('<p style="color:red;text-align:center;">The mobile number already exists!</p>');
        }

        if (strlen($mobile) != 10) {
            die('<p style="color:red;text-align:center;">Mobile number must be 10 digits!</p>');
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            die('<p style="color:red;text-align:center;">Invalid email address!</p>');
        }

        $result = $this->queries->registerSupplier($_POST);

        if ($result['success']) {
            echo '<p style="color:green;text-align:center;">' . $result['message'] . '</p>';
            echo '<script>setTimeout(function(){ location.reload(); }, 2000);</script>';
        } else {
            echo '<p style="color:red;text-align:center;">' . $result['message'] . '</p>';
        }
    }
}

$auth = new SupplierAuth();
$auth->handleRequest();
?>
