<?php
require_once __DIR__ . '/../common/db.php';

class RegistrationQueries {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function checkMobileExists($mobile) {
        // Check approved details
        $query = "SELECT count(msd_mobileno) AS numbercount FROM mms_suppliers_details WHERE msd_mobileno = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $mobile);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row['numbercount'] != 0) return true;
        
        // Check pending details
        $query = "SELECT count(msd_mobileno) AS numbercount FROM mms_supplier_pending_details WHERE msd_mobileno = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $mobile);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return ($row['numbercount'] != 0);
    }
    
    public function registerSupplier($data) {
        $createddate = date('Y-m-d');
        $uid = time();
        
        $query = "INSERT INTO mms_supplier_pending_details 
                  (msd_supplier_code, msd_supplier_name, msd_email_address, msd_mobileno, msd_supply_category, msd_supply_category_des, msd_address, msd_status, created_date) 
                  VALUES (?, UPPER(?), ?, ?, ?, ?, UPPER(?), 'I', ?)";
                  
        $stmt = $this->db->prepare($query);
        if (!$stmt) return false;
        
        $stmt->bind_param('ssssssss', 
            $uid, $data['supname'], $data['email'], $data['mobile'], 
            $data['supcat'], $data['description'], $data['address'], $createddate
        );
        
        return $stmt->execute();
    }
}
?>
