<?php
require_once __DIR__ . '/../common/db.php';

class TenderStatusQueries {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getActiveTenders() {
        $query = "SELECT * FROM mms_tender_details WHERE mtd_status = 'A'";
        $result = $this->db->query($query);
        $tenders = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tenders[] = $row;
            }
        }
        return $tenders;
    }
    
    public function closeTender($tender) {
        $this->db->begin_transaction();
        try {
            // 1. Move to history/audit table
            $sql = "INSERT INTO cronjob_mms_tenderprice_transaction (mtt_year, mtt_tender_no, mtt_supplier_code, mtt_material_code, mtt_price, mtt_status, created_by, created_date)  
                    SELECT mtt_year, mtt_tender_no, mtt_supplier_code, mtt_material_code, mtt_price, mtt_status, created_by, created_date 
                    FROM mms_tenderprice_transactions 
                    WHERE mtt_year = ? AND mtt_tender_no = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ss', $tender['mtd_year'], $tender['mtd_tender_no']);
            $stmt->execute();
            
            // 2. Update status to Inactive
            $sql = "UPDATE mms_tender_details SET mtd_status = 'I' WHERE mtd_year = ? AND mtd_tender_no = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ss', $tender['mtd_year'], $tender['mtd_tender_no']);
            $stmt->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}
?>
