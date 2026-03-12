<?php
require_once __DIR__ . '/../common/db.php';

class DashboardQueries {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getSupplierCategory($supplierCode) {
        // First check pending details table
        $query = "SELECT msd_supply_category FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('s', $supplierCode);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc()['msd_supply_category'];
            }
        }
        
        // Then check approved details table
        $query = "SELECT msd_supply_category FROM mms_suppliers_details WHERE msd_supplier_code = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param('s', $supplierCode);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc()['msd_supply_category'];
            }
        }
        
        return null;
    }

    public function getCategoriesBySupplierCategory($category) {
        $query = "SELECT cat_code, display_name, image_path, sort_order
                  FROM mms_category_forms
                  WHERE supplier_category = ?
                  ORDER BY sort_order";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return [];
        
        $stmt->bind_param('s', $category);
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

    public function getUnitForCategory($catCode) {
        $query = "SELECT DISTINCT MMC_UNIT FROM mms_material_catalogue WHERE MMC_CAT_CODE = ? AND MMC_STATUS = 'A' LIMIT 1";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return '';
        
        $stmt->bind_param('s', $catCode);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc()['MMC_UNIT'];
        }
        return '';
    }

    public function getActiveTender($userCategory) {
        if ($userCategory === 'RI') {
            $query = "SELECT mtd_tender_no, mtd_year, mtd_start_date, mtd_end_date, mtd_bidclose_date FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1";
            $stmt = $this->db->prepare($query);
        } else {
            $query = "SELECT mtd_tender_no, mtd_year, mtd_start_date, mtd_end_date, mtd_bidclose_date FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = ? LIMIT 1";
            $stmt = $this->db->prepare($query);
            if ($stmt) $stmt->bind_param('s', $userCategory);
        }
        
        if (!$stmt) return null;
        
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }

    public function saveTenderPrices($tenderYear, $tenderNo, $supplierCode, $itemsData) {
        $success = true;
        $dateNow = date('Y-m-d g:i A');
        $status = 'A';

        // Prepare statements once
        $selectStmt = $this->db->prepare("SELECT mtt_price, mtt_remark FROM mms_tenderprice_transactions WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A' LIMIT 1");
        $deleteStmt = $this->db->prepare("DELETE FROM mms_tenderprice_transactions WHERE mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A' AND mtt_tender_no=?");
        $updateStmt = $this->db->prepare("UPDATE mms_tenderprice_transactions SET mtt_remark=?, mtt_price=?, updated_by=?, updated_date=? WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A'");
        $insertStmt = $this->db->prepare("INSERT INTO mms_tenderprice_transactions (mtt_year,mtt_tender_no,mtt_supplier_code,mtt_material_code,mtt_remark,mtt_price,mtt_status,created_by,created_date) VALUES (?,?,?,?,?,?,?,?,?)");

        $this->db->begin_transaction();

        try {
            foreach ($itemsData as $item) {
                if (empty($item['MMC_MATERIAL_CODE'])) continue;

                // Delete transaction if price is removed
                if ($item['MMC_PRICE'] === null) {
                    $deleteStmt->bind_param('sss', $supplierCode, $item['MMC_MATERIAL_CODE'], $tenderNo);
                    if (!$deleteStmt->execute()) $success = false;
                    continue;
                }

                // Check existing data
                $selectStmt->bind_param('ssss', $tenderYear, $tenderNo, $supplierCode, $item['MMC_MATERIAL_CODE']);
                if (!$selectStmt->execute()) { $success = false; continue; }
                
                $result = $selectStmt->get_result();
                $existing = $result->fetch_assoc();

                if ($existing) {
                    // Update if changed
                    if ((string)$existing['mtt_price'] !== (string)$item['MMC_PRICE'] || (string)$existing['mtt_remark'] !== (string)$item['MMC_REMARK']) {
                        $updateStmt->bind_param('sdssssss', 
                            $item['MMC_REMARK'], $item['MMC_PRICE'], $supplierCode, $dateNow, 
                            $tenderYear, $tenderNo, $supplierCode, $item['MMC_MATERIAL_CODE']
                        );
                        if (!$updateStmt->execute()) $success = false;
                    }
                } else {
                    // Insert new price
                    $insertStmt->bind_param('sssssdsss', 
                        $tenderYear, $tenderNo, $supplierCode, $item['MMC_MATERIAL_CODE'], 
                        $item['MMC_REMARK'], $item['MMC_PRICE'], $status, $supplierCode, $dateNow
                    );
                    if (!$insertStmt->execute()) $success = false;
                }
            }
            
            if ($success) {
                $this->db->commit();
            } else {
                $this->db->rollback();
            }
        } catch (Exception $e) {
            $this->db->rollback();
            $success = false;
        }

        return $success;
    }

    public function getCategoryItems($catCode, $supplierCode, $userCategory) {
        if ($userCategory === 'RI') {
            $tenderSubquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1)";
        } else {
            // Escape user category properly to avoid SQL injection since we inject it into the subquery
            $escaped_cat = $this->db->real_escape_string($userCategory);
            $tenderSubquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$escaped_cat' LIMIT 1)";
        }
        
        $escaped_sup = $this->db->real_escape_string($supplierCode);
        $escaped_catCode = $this->db->real_escape_string($catCode);

        $query = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE, MMC_CAT_CODE,
                         mtt_price AS MMC_PRICE, mtt_remark AS MMC_REMARK
                  FROM mms_material_catalogue
                  LEFT JOIN mms_tenderprice_transactions
                      ON mtt_material_code = MMC_MATERIAL_CODE
                      AND mtt_supplier_code = '$escaped_sup'
                      AND mtt_tender_no = $tenderSubquery
                  WHERE MMC_CAT_CODE = '$escaped_catCode' AND MMC_STATUS = 'A'
                  ORDER BY MMC_DESCRIPTION ASC";
                  
        $result = $this->db->query($query);
        $items = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items;
    }

    public function checkTenderPrices($supplierCode, $userCategory) {
        if ($userCategory === 'RI') {
            $query = "SELECT mtt_price FROM mms_tenderprice_transactions 
                      WHERE mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL) 
                      AND mtt_supplier_code = ?";
            $stmt = $this->db->prepare($query);
        } else {
            $query = "SELECT mtt_price FROM mms_tenderprice_transactions 
                      WHERE mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = ?) 
                      AND mtt_supplier_code = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt) $stmt->bind_param('ss', $userCategory, $supplierCode);
        }
        
        if (!$stmt) return false;
        
        if ($userCategory === 'RI') {
            $stmt->bind_param('s', $supplierCode);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return ($row !== null && $row['mtt_price'] !== null);
    }

    public function submitTender($supplierCode, $userCategory) {
        $dateNow = date('Y-m-d g:i A');
        
        // 1. Get Tender Info
        $activeTender = $this->getActiveTender($userCategory);
        if (!$activeTender) return false;
        
        $tenderNo = $activeTender['mtd_tender_no'];
        $tenderYear = $activeTender['mtd_year'];
        
        // 2. Check existing submission
        $query = "SELECT * FROM mms_suptender_details WHERE msd_year = ? AND msd_tender_no = ? AND msd_supplier_code = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return false;
        
        $stmt->bind_param('sss', $tenderYear, $tenderNo, $supplierCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing = $result->fetch_assoc();
        
        if ($existing) {
            $updateQuery = "UPDATE mms_suptender_details SET updated_by = ?, updated_date = ? WHERE msd_year = ? AND msd_tender_no = ? AND msd_supplier_code = ?";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bind_param('sssss', $supplierCode, $dateNow, $tenderYear, $tenderNo, $supplierCode);
            return $updateStmt->execute();
        } else {
            $insertQuery = "INSERT INTO mms_suptender_details (msd_year, msd_tender_no, msd_supplier_code, msd_status, created_by, created_date) VALUES (?, ?, ?, 'A', ?, ?)";
            $insertStmt = $this->db->prepare($insertQuery);
            $insertStmt->bind_param('sssss', $tenderYear, $tenderNo, $supplierCode, $supplierCode, $dateNow);
            return $insertStmt->execute();
        }
    }

    public function getSavedTenderItems($supplierCode, $userCategory) {
        $activeTender = $this->getActiveTender($userCategory);
        if (!$activeTender) return [];
        
        $tenderNo = $activeTender['mtd_tender_no'];
        
        // Get category names
        $query = "SELECT cat_code, display_name FROM mms_category_forms WHERE supplier_category = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $userCategory);
        $stmt->execute();
        $catResult = $stmt->get_result();
        $catNames = [];
        while ($row = $catResult->fetch_assoc()) {
            $catNames[$row['cat_code']] = $row['display_name'];
        }
        
        if (empty($catNames)) return [];
        
        $catCodes = array_keys($catNames);
        $catList = "'" . implode("','", $catCodes) . "'";
        
        $query = "SELECT c.MMC_CAT_CODE, c.MMC_DESCRIPTION, t.mtt_price
                  FROM mms_material_catalogue c
                  INNER JOIN mms_tenderprice_transactions t 
                      ON t.mtt_material_code = c.MMC_MATERIAL_CODE 
                      AND t.mtt_supplier_code = ?
                      AND t.mtt_tender_no = ?
                      AND t.mtt_status = 'A'
                  WHERE c.MMC_CAT_CODE IN ($catList) AND c.MMC_STATUS = 'A'
                  ORDER BY c.MMC_CAT_CODE, c.MMC_DESCRIPTION";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $supplierCode, $tenderNo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = [
                'CategoryName' => $catNames[$row['MMC_CAT_CODE']] ?? $row['MMC_CAT_CODE'],
                'MMC_DESCRIPTION' => $row['MMC_DESCRIPTION'],
                'mtt_price' => $row['mtt_price']
            ];
        }
        return $items;
    }

    public function getSavedCategoryItems($supplierCode, $userCategory, $catCode) {
        $activeTender = $this->getActiveTender($userCategory);
        if (!$activeTender) return [];
        
        $tenderNo = $activeTender['mtd_tender_no'];
        
        $query = "SELECT MMC_DESCRIPTION, mtt_price 
                  FROM mms_material_catalogue
                  INNER JOIN mms_tenderprice_transactions 
                      ON mtt_material_code = MMC_MATERIAL_CODE 
                      AND mtt_supplier_code = ?
                      AND mtt_tender_no = ?
                      AND mtt_status = 'A'
                  WHERE MMC_CAT_CODE = ? AND MMC_STATUS = 'A'
                  ORDER BY MMC_DESCRIPTION ASC";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sss', $supplierCode, $tenderNo, $catCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }
}
?>
