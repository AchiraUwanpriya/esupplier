<?php
require_once __DIR__ . '/../common/db.php';
require_once __DIR__ . '/../common/helper.php';
require_once __DIR__ . '/../common/category_queries.php';

class SupplierActions {
    private $conn;
    private $categoryQueries;
    
    public function __construct() {
        $this->conn = Database::getInstance();
        $this->categoryQueries = new CategoryQueries();
    }
    
    public function registerSupplier($data) {
        // Validate required fields
        $required = ['supname', 'supcat', 'description', 'address', 'mobile', 'email'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => ucfirst($field) . " is required"];
            }
        }
        
        // Validate mobile number
        if (!preg_match('/^[0][7][0-9]{8}$/', $data['mobile'])) {
            return ['success' => false, 'message' => 'Invalid mobile number format. Must start with 07 and be 10 digits'];
        }
        
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        // Check if mobile already exists
        $checkQuery = "SELECT msd_supplier_code FROM mms_supplier_pending_details WHERE msd_mobileno = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $data['mobile']);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            return ['success' => false, 'message' => 'Mobile number already registered'];
        }
        
        // Validate reCAPTCHA
        if (!isset($_POST['g-recaptcha-response'])) {
            return ['success' => false, 'message' => 'Please complete the reCAPTCHA'];
        }
        
        // Verify reCAPTCHA
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $recaptcha_secret = RECAPTCHA_SECRET;
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
        
        if ($recaptcha_result === FALSE) {
            return ['success' => false, 'message' => 'reCAPTCHA verification failed'];
        }
        
        $recaptcha_json = json_decode($recaptcha_result);
        
        if (!$recaptcha_json->success) {
            return ['success' => false, 'message' => 'reCAPTCHA verification failed'];
        }
        
        // Get or create category
        $category_id = null;
        $categoryQuery = "SELECT category_id FROM mms_categories WHERE category_name = ?";
        $catStmt = $this->conn->prepare($categoryQuery);
        $catStmt->bind_param("s", $data['supcat']);
        $catStmt->execute();
        $catResult = $catStmt->get_result();
        
        if ($catResult->num_rows > 0) {
            $category = $catResult->fetch_assoc();
            $category_id = $category['category_id'];
        } else {
            // Create new category if it doesn't exist
            $insertCat = "INSERT INTO mms_categories (category_name, status) VALUES (?, 1)";
            $insertCatStmt = $this->conn->prepare($insertCat);
            $insertCatStmt->bind_param("s", $data['supcat']);
            if ($insertCatStmt->execute()) {
                $category_id = $this->conn->insert_id;
            }
        }
        
        // Insert into database with category_id
        $tsql = "INSERT INTO mms_supplier_pending_details 
                (msd_supplier_name, msd_supplier_cat, msd_category_id, msd_description, msd_address, msd_mobileno, msd_email, registration_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($tsql);
        $stmt->bind_param("ssissss", 
            $data['supname'],
            $data['supcat'],
            $category_id,
            $data['description'],
            $data['address'],
            $data['mobile'],
            $data['email']
        );
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Registration successful! You can now login.'];
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $this->conn->error];
        }
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier = new SupplierActions();
    
    if (isset($_POST['insertbtn'])) {
        $result = $supplier->registerSupplier($_POST);
        
        // Return HTML response
        if ($result['success']) {
            echo '<div style="color: green; font-weight: bold; padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">' . $result['message'] . '</div>';
        } else {
            echo '<div style="color: red; font-weight: bold; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;">' . $result['message'] . '</div>';
        }
        exit();
    }
}
?>