<?php
require_once '../common/db.php';
require_once '../common/helper.php';

class SupplierActions {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function registerSupplier($data) {
        // Validate required fields
        $required = ['supname', 'supcat', 'description', 'address', 'mobile', 'email'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => "$field is required"];
            }
        }
        
        // Validate mobile number
        if (!preg_match('/^[0][7][0-9]{8}$/', $data['mobile'])) {
            return ['success' => false, 'message' => 'Invalid mobile number format'];
        }
        
        // Validate reCAPTCHA
        if (!isset($_POST['g-recaptcha-response'])) {
            return ['success' => false, 'message' => 'Please complete the reCAPTCHA'];
        }
        
        // Verify reCAPTCHA
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $recaptcha_secret = '6LeyhpcgAAAAAEdH8eXbOd2HGIPQbhB_jeeKYjlH'; // Move to config file
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_data = [
            'secret' => $recaptcha_secret,
            'response' => $recaptcha_response
        ];
        
        $recaptcha_options = [
            'http' => [
                'method' => 'POST',
                'content' => http_build_query($recaptcha_data)
            ]
        ];
        
        $recaptcha_context = stream_context_create($recaptcha_options);
        $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
        $recaptcha_json = json_decode($recaptcha_result);
        
        if (!$recaptcha_json->success) {
            return ['success' => false, 'message' => 'reCAPTCHA verification failed'];
        }
        
        // Insert into database
        $tsql = "INSERT INTO mms_supplier_pending_details 
                (msd_supplier_name, msd_supplier_cat, msd_description, msd_address, msd_mobileno, msd_email) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($tsql);
        $stmt->bind_param("ssssss", 
            $data['supname'],
            $data['supcat'],
            $data['description'],
            $data['address'],
            $data['mobile'],
            $data['email']
        );
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Registration successful!'];
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $this->db->error];
        }
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier = new SupplierActions();
    
    if (isset($_POST['insertbtn'])) {
        $result = $supplier->registerSupplier($_POST);
        
        if ($result['success']) {
            header('Content-Type: text/html');
            echo '<div style="color: green; font-weight: bold;">' . $result['message'] . '</div>';
        } else {
            header('Content-Type: text/html');
            echo '<div style="color: red; font-weight: bold;">' . $result['message'] . '</div>';
        }
        exit();
    }
}
?>