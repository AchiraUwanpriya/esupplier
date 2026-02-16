<?php
require_once '../common/db.php';

class SupplierQueries {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getSupplierByMobile($mobile) {
        $tsql = "SELECT * FROM mms_supplier_pending_details WHERE msd_mobileno = ?";
        $stmt = $this->db->prepare($tsql);
        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getSupplierById($id) {
        $tsql = "SELECT * FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
        $stmt = $this->db->prepare($tsql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAllSuppliers() {
        $tsql = "SELECT * FROM mms_supplier_pending_details ORDER BY msd_supplier_code DESC";
        $result = $this->db->query($tsql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?> 