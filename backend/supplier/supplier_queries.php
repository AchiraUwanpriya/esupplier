<?php
require_once __DIR__ . '/../common/db.php';

class SupplierQueries {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getSupplierByMobile($mobile) {
        // First check active/approved suppliers table
        $tsql1 = "SELECT msd_supplier_code, msd_supplier_name, msd_mobileno, msd_status, msd_supply_category
                  FROM mms_suppliers_details 
                  WHERE msd_mobileno = ?
                  LIMIT 1";
        
        $stmt1 = $this->db->prepare($tsql1);
        if ($stmt1) {
            $stmt1->bind_param("s", $mobile);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            if ($result1 && $result1->num_rows > 0) {
                return $result1->fetch_assoc();
            }
        }

        // Then check pending suppliers table
        $tsql2 = "SELECT msd_supplier_code, msd_supplier_name, msd_mobileno, msd_status, msd_supply_category
                  FROM mms_supplier_pending_details 
                  WHERE msd_mobileno = ?
                  LIMIT 1";
        
        $stmt2 = $this->db->prepare($tsql2);
        if (!$stmt2) return null;
        
        $stmt2->bind_param("s", $mobile);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        return $result2 ? $result2->fetch_assoc() : null;
    }
    
    public function getSupplierById($id) {
        $tsql = "SELECT * FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
        $stmt = $this->db->prepare($tsql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAllSuppliers() {
        $tsql = "SELECT * FROM mms_supplier_pending_details ORDER BY created_date DESC, msd_supplier_code DESC";
        $result = $this->db->query($tsql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingSuppliers() {
        $tsql = "SELECT *,
                    CASE
                        WHEN msd_supply_category = 'RI' THEN 'Ration Items'
                        WHEN msd_supply_category = 'PI' THEN 'Pvc Items'
                        ELSE msd_supply_category
                    END AS msd_supply_category_label
                 FROM mms_supplier_pending_details
                 WHERE msd_status = 'I'
                 ORDER BY created_date DESC, msd_supplier_code DESC";

        $result = $this->db->query($tsql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function approvePendingSupplier($supplierData) {
        $required = ['supplier_code', 'supplier_name', 'email', 'mobile', 'category', 'address'];
        foreach ($required as $field) {
            if (!isset($supplierData[$field]) || $supplierData[$field] === '') {
                return ['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'];
            }
        }

        try {
            $this->db->begin_transaction();

            $insertSql = "INSERT INTO mms_suppliers_details
                (msd_supplier_code, msd_supplier_name, msd_email_address, msd_mobileno, msd_supply_category, msd_supply_category_des, msd_address, msd_status, created_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'A', NOW())";

            $insertStmt = $this->db->prepare($insertSql);
            if (!$insertStmt) {
                throw new Exception($this->db->error);
            }

            $categoryDescription = isset($supplierData['category_description']) ? $supplierData['category_description'] : '';

            $insertStmt->bind_param(
                "sssssss",
                $supplierData['supplier_code'],
                $supplierData['supplier_name'],
                $supplierData['email'],
                $supplierData['mobile'],
                $supplierData['category'],
                $categoryDescription,
                $supplierData['address']
            );

            if (!$insertStmt->execute()) {
                throw new Exception($insertStmt->error);
            }

            $deleteSql = "DELETE FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
            $deleteStmt = $this->db->prepare($deleteSql);
            if (!$deleteStmt) {
                throw new Exception($this->db->error);
            }

            $deleteStmt->bind_param("s", $supplierData['supplier_code']);
            if (!$deleteStmt->execute()) {
                throw new Exception($deleteStmt->error);
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Supplier approved successfully'];
        } catch (Throwable $exception) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Approval failed: ' . $exception->getMessage()];
        }
    }

    public function deletePendingSupplier($supplierCode) {
        try {
            $deleteSql = "DELETE FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
            $deleteStmt = $this->db->prepare($deleteSql);

            if (!$deleteStmt) {
                throw new Exception($this->db->error);
            }

            $deleteStmt->bind_param("s", $supplierCode);

            if (!$deleteStmt->execute()) {
                throw new Exception($deleteStmt->error);
            }

            return ['success' => true, 'message' => 'Supplier removed successfully'];
        } catch (Throwable $exception) {
            return ['success' => false, 'message' => 'Delete failed: ' . $exception->getMessage()];
        }
    }

    public function checkMobileExists($mobile) {
        // Check in active suppliers
        $query1 = "SELECT count(msd_mobileno) AS numbercount FROM mms_suppliers_details WHERE msd_mobileno = ?";
        $stmt1 = $this->db->prepare($query1);
        $stmt1->bind_param("s", $mobile);
        $stmt1->execute();
        $row1 = $stmt1->get_result()->fetch_object();

        // Check in pending suppliers
        $query2 = "SELECT count(msd_mobileno) AS numbercount FROM mms_supplier_pending_details WHERE msd_mobileno = ?";
        $stmt2 = $this->db->prepare($query2);
        $stmt2->bind_param("s", $mobile);
        $stmt2->execute();
        $row2 = $stmt2->get_result()->fetch_object();

        return ($row1->numbercount != 0 || $row2->numbercount != 0);
    }

    public function registerSupplier($data) {
        $uid = time();
        $createddate = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO mms_supplier_pending_details 
                 (msd_supplier_code, msd_supplier_name, msd_email_address, msd_mobileno, msd_supply_category, msd_supply_category_des, msd_address, msd_status, created_date) 
                 VALUES (?, UPPER(?), ?, ?, ?, ?, UPPER(?), 'I', ?)";
        
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => $this->db->error];
        }

        $stmt->bind_param(
            "isssssss",
            $uid,
            $data['supname'],
            $data['email'],
            $data['mobile'],
            $data['supcat'],
            $data['description'],
            $data['address'],
            $createddate
        );

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Successfully Registered! We will get back to you soon!'];
        } else {
            return ['success' => false, 'message' => $stmt->error];
        }
    }
}
?> 