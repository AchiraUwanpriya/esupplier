<?php
require_once __DIR__ . '/../common/db.php';

class ProfileQueries {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // FETCH methods
    
    public function getProfileDetails($supplierCode) {
        $query = "SELECT * FROM mms_suppliers_details WHERE msd_supplier_code = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return null;
        
        $stmt->bind_param('s', $supplierCode);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }
    
    public function getSupplierBankDetails($supplierCode) {
        $query = "SELECT MMSSB.*, MMSDB.MBD_BANK_NAME, MMSDB2.MBD_BANK_NAME AS BRANCH_NAME FROM mms_supplier_banks MMSSB 
                  LEFT JOIN mms_bank_details MMSDB ON MMSDB.MBD_CHILD_KEY = MMSSB.MSB_MAIN_BANK_CODE 
                  LEFT JOIN mms_bank_details MMSDB2 ON MMSDB2.MBD_CHILD_KEY = MMSSB.MSB_CHILD_KEY
                  WHERE MMSSB.MSB_SUPPLIER_CODE = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return null;
        
        $stmt->bind_param('s', $supplierCode);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }
    
    public function getAllBanks() {
        $query = "SELECT MBD_CHILD_KEY, MBD_BANK_NAME FROM mms_bank_details
                  WHERE MBD_BANK_TYPE = 'L' AND MBD_STATUS = 'A' AND MBD_PARENT_KEY IS NULL";
        $result = $this->db->query($query);
        
        $banks = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $banks[] = $row;
            }
        }
        return $banks;
    }
    
    public function getBankBranches($bankCode) {
        $query = "SELECT MBD_CHILD_KEY, MBD_BANK_NAME FROM mms_bank_details 
                  WHERE MBD_BANK_TYPE = 'L' AND MBD_STATUS = 'A' AND MBD_PARENT_KEY = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return [];
        
        $stmt->bind_param('s', $bankCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $branches = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $branches[] = $row;
            }
        }
        return $branches;
    }
    
    public function getTaxDetails($supplierCode) {
        $query = "SELECT * FROM mms_tax_details WHERE msd_supplier_code = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return null;
        
        $stmt->bind_param('s', $supplierCode);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }
    
    public function getSupplierTenderCategories($supplierCode) {
        $query = "SELECT DISTINCT MMC_CAT_CODE, 
                         (SELECT mtc_description FROM mms_tendermaterial_categories WHERE mtc_cat_code = MMC_CAT_CODE) AS CATDESC 
                  FROM mms_tenderprice_transactions 
                  LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
                  WHERE mms_tenderprice_transactions.mtt_supplier_code = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return [];
        
        $stmt->bind_param('s', $supplierCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }
    
    public function getSupplierAttachments($supplierCode) {
        $query = "SELECT msd_serial_no, msd_file_name, msd_file_path, msd_status 
                  FROM mms_supplier_attachments 
                  WHERE msd_sup_code = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return [];
        
        $stmt->bind_param('s', $supplierCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $attachments = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $attachments[] = $row;
            }
        }
        return $attachments;
    }
    
    // UPDATE methods
    
    public function updateProfileDetails($supplierCode, $data) {
        $query = "UPDATE mms_suppliers_details SET 
                  msd_business_nature = ?,
                  msd_country_code = '+94',
                  msd_address = ?,
                  msd_officeaddress = ?,
                  msd_operationaddress = ?, 
                  msd_postalcode = ?,
                  msd_teleno = ?,
                  msd_faxno = ?,
                  msd_email_address = ?,
                  msd_website = ?,
                  msd_contact_person = ?,
                  msd_agent = ?
                  WHERE msd_supplier_code = ?";
                  
        $stmt = $this->db->prepare($query);
        if (!$stmt) return false;
        
        $stmt->bind_param('ssssssssssss', 
            $data['bsnature'], $data['address'], $data['officeaddress'], 
            $data['operationaddress'], $data['postalCode'], $data['telnumber'], 
            $data['fax'], $data['emailad'], $data['web'], $data['contactperson'], 
            $data['agent'], $supplierCode
        );
        
        return $stmt->execute();
    }
    
    public function updateBankDetails($supplierCode, $data) {
        $createddate = date('Y-m-d');
        
        if (empty($data['supbankid'])) {
            $query = "INSERT INTO mms_supplier_banks 
                      (MSB_SUPPLIER_CODE, MSB_MAIN_BANK_CODE, MSB_BANK_CODE, MSB_CHILD_KEY, MSB_ACCOUNT_NO, MSB_ACCOUNT_TYPE, CREATED_BY, CREATED_DATE) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if (!$stmt) return false;
            
            $stmt->bind_param('ssssssss', 
                $supplierCode, $data['mainbank'], $data['bankcode'], 
                $data['branch'], $data['accnumber'], $data['acctype'], 
                $supplierCode, $createddate
            );
        } else {
            $query = "UPDATE mms_supplier_banks SET 
                      MSB_CHILD_KEY = ?,
                      MSB_ACCOUNT_NO = ?,
                      MSB_ACCOUNT_TYPE = ?,
                      MSB_MAIN_BANK_CODE = ?,
                      MSB_BANK_CODE = ?,
                      UPDATED_BY = ?,
                      UPDATED_DATE = ?
                      WHERE MSB_SUPPLIER_BANK_ID = ?";
            $stmt = $this->db->prepare($query);
            if (!$stmt) return false;
            
            $stmt->bind_param('sssssssi', 
                $data['branch'], $data['accnumber'], $data['acctype'], 
                $data['mainbank'], $data['bankcode'], $supplierCode, 
                $createddate, $data['supbankid']
            );
        }
        
        return $stmt->execute();
    }
    
    public function updateTaxDetails($supplierCode, $data) {
        $createddate = date('Y-m-d');
        
        if (empty($data['msdid'])) {
            $query = "INSERT INTO mms_tax_details (msd_supplier_code, msd_vat, msd_svat, created_by, created_date) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if (!$stmt) return false;
            
            $stmt->bind_param('sssss', 
                $supplierCode, $data['VAT'], $data['SVAT'], 
                $supplierCode, $createddate
            );
        } else {
            $query = "UPDATE mms_tax_details SET 
                      msd_supplier_code = ?,
                      msd_vat = ?,
                      msd_svat = ?,
                      updated_by = ?,
                      updated_date = ?
                      WHERE msd_id = ?";
            $stmt = $this->db->prepare($query);
            if (!$stmt) return false;
            
            $stmt->bind_param('sssssi', 
                $supplierCode, $data['VAT'], $data['SVAT'], 
                $supplierCode, $createddate, $data['msdid']
            );
        }
        
        return $stmt->execute();
    }

    public function updateMaterialDoc($supplierCode, $fileName) {
        $query = "UPDATE mms_suppliers_details SET msd_doc_url = ? WHERE msd_supplier_code = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return false;
        
        $stmt->bind_param('ss', $fileName, $supplierCode);
        return $stmt->execute();
    }
}
?>
