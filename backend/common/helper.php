<?php
// backend/common/helper.php

class Helper {
    public static function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }
    
    public static function generateOTP($length = 6) {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }
    
    public static function redirect($url) {
        header("Location: $url");
        exit();
    }
    
    public static function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    public static function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * Verify reCAPTCHA
     */
    public static function verifyRecaptcha($response) {
        if (empty($response)) {
            return false;
        }
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => RECAPTCHA_SECRET,
            'response' => $response
        ];
        
        $options = [
            'http' => [
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === FALSE) {
            return false;
        }
        
        $json = json_decode($result);
        return $json->success;
    }
    
    /**
     * Get category ID from supplier code
     * Maps supplier category (RI, PI, MI, CB) to category_id in mms_categories table
     */
    public static function getCategoryIdFromSupplierCode($con, $supplier_code) {
        if (empty($supplier_code)) {
            return 1; // Default to Ration Items
        }
        
        // Get supplier's category code from database
        $category_code = '';
        
        // Check pending details first
        $query = "SELECT msd_supply_category FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
        $stmt = $con->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $supplier_code);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $category_code = $row['msd_supply_category'];
            }
            $stmt->close();
        }
        
        // If not found in pending, check approved suppliers
        if (empty($category_code)) {
            $query = "SELECT msd_supply_category FROM mms_suppliers_details WHERE msd_supplier_code = ?";
            $stmt = $con->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $supplier_code);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $category_code = $row['msd_supply_category'];
                }
                $stmt->close();
            }
        }
        
        // Map category code to category ID based on mms_categories table
        $category_code = strtoupper(trim($category_code));
        
        switch ($category_code) {
            case 'RI':
                return 1; // Ration Items
            case 'PI':
                return 2; // PVC Items
            case 'MI':
                return 3; // Medicine Items
            case 'CB':
                return 4; // Cables
            default:
                return 1; // Default to Ration Items
        }
    }
    
    /**
     * Get supplier's category name from database
     */
    public static function getSupplierCategoryName($con, $supplier_code) {
        if (empty($supplier_code)) {
            return '';
        }
        
        // Check pending details first
        $query = "SELECT msd_supply_category FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
        $stmt = $con->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $supplier_code);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                return $row['msd_supply_category'];
            }
            $stmt->close();
        }
        
        // Check approved suppliers
        $query = "SELECT msd_supply_category FROM mms_suppliers_details WHERE msd_supplier_code = ?";
        $stmt = $con->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $supplier_code);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                return $row['msd_supply_category'];
            }
            $stmt->close();
        }
        
        return '';
    }
    
    /**
     * Get category name from category ID
     */
    public static function getCategoryNameFromId($con, $category_id) {
        $query = "SELECT category_name FROM mms_categories WHERE category_id = ? LIMIT 1";
        $stmt = $con->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                return $row['category_name'];
            }
            $stmt->close();
        }
        return 'General Items';
    }
}
?>