<?php
require_once '../common/db.php';
require_once '../common/helper.php';

class AuthHandler {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login($mobile) {
        $tsql = "SELECT msd_supplier_code, msd_mobileno, msd_supplier_name 
                FROM mms_supplier_pending_details 
                WHERE msd_mobileno = ?";
        
        $stmt = $this->db->prepare($tsql);
        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION["msd_supplier_name"] = $user['msd_supplier_name'];
            $_SESSION["msd_supplier_code"] = $user['msd_supplier_code'];
            return true;
        }
        return false;
    }
    
    public function logout() {
        session_destroy();
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['msd_supplier_name']);
    }
}

// Handle login POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $auth = new AuthHandler();
    
    if ($_POST['action'] === 'login' && isset($_POST['mobile'])) {
        if ($auth->login($_POST['mobile'])) {
            echo json_encode(['success' => true, 'redirect' => '../public/dashboard.php']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid mobile number']);
        }
    }
}
?>