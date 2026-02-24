<?php
/**
 * Admin Dashboard Database Queries
 * All queries use prepared statements for security
 */

/**
 * Get active tender by category/type
 * Special handling: RI category matches tenders where mtd_type IS NULL
 * @param mysqli $con Database connection
 * @param string $categoryCode Supplier category (RI, PI, MI, CB, etc.)
 * @return array ['tenderNo' => '', 'tenderYear' => ''] or empty array
 */
function getTenderByCategory($con, $categoryCode)
{
    if ($categoryCode === 'RI') {
        // RI (general) -> match tenders where mtd_type is NULL/empty
        $query = "SELECT mtd_tender_no, mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1";
        $stmt = $con->prepare($query);
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // category-specific tenders (PI, MI, CB, etc.)
        $query = "SELECT mtd_tender_no, mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = ? LIMIT 1";
        $stmt = $con->prepare($query);
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->bind_param("s", $categoryCode);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'tenderNo' => $row['mtd_tender_no'],
            'tenderYear' => $row['mtd_year']
        ];
    }
    
    return [];
}

/**
 * Get existing price and remark for a material
 * @param mysqli $con Database connection
 * @param string $tenderYear Tender year
 * @param string $tenderNo Tender number
 * @param string $supplierCode Supplier code
 * @param string $materialCode Material code
 * @return array ['price' => '', 'remark' => ''] or empty
 */
function getExistingPrice($con, $tenderYear, $tenderNo, $supplierCode, $materialCode)
{
    $query = "SELECT mtt_price, mtt_remark FROM mms_tenderprice_transactions 
              WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A' 
              LIMIT 1";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("ssss", $tenderYear, $tenderNo, $supplierCode, $materialCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'price' => $row['mtt_price'],
            'remark' => $row['mtt_remark']
        ];
    }
    
    return [];
}

/**
 * Delete price transaction
 * @param mysqli $con Database connection
 * @param string $supplierCode Supplier code
 * @param string $materialCode Material code
 * @param string $tenderNo Tender number
 * @return bool Success status
 */
function deletePriceTransaction($con, $supplierCode, $materialCode, $tenderNo)
{
    $query = "DELETE FROM mms_tenderprice_transactions 
              WHERE mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A' AND mtt_tender_no=?";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("sss", $supplierCode, $materialCode, $tenderNo);
    return $stmt->execute();
}

/**
 * Update price transaction
 * @param mysqli $con Database connection
 * @param string $remark Remark/note
 * @param float $price Price
 * @param string $updatedBy Updated by user code
 * @param string $tenderYear Tender year
 * @param string $tenderNo Tender number
 * @param string $supplierCode Supplier code
 * @param string $materialCode Material code
 * @return bool Success status
 */
function updatePriceTransaction($con, $remark, $price, $updatedBy, $tenderYear, $tenderNo, $supplierCode, $materialCode)
{
    $dateNow = date('Y-m-d g:i A');
    
    $query = "UPDATE mms_tenderprice_transactions 
              SET mtt_remark=?, mtt_price=?, updated_by=?, updated_date=? 
              WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A'";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("ddsssss", $remark, $price, $updatedBy, $dateNow, $tenderYear, $tenderNo, $supplierCode, $materialCode);
    return $stmt->execute();
}

/**
 * Insert price transaction
 * @param mysqli $con Database connection
 * @param string $tenderYear Tender year
 * @param string $tenderNo Tender number
 * @param string $supplierCode Supplier code
 * @param string $materialCode Material code
 * @param string $remark Remark/note
 * @param float $price Price
 * @param string $createdBy Created by user code
 * @return bool Success status
 */
function insertPriceTransaction($con, $tenderYear, $tenderNo, $supplierCode, $materialCode, $remark, $price, $createdBy)
{
    $status = 'A';
    $dateNow = date('Y-m-d g:i A');
    
    $query = "INSERT INTO mms_tenderprice_transactions 
              (mtt_year, mtt_tender_no, mtt_supplier_code, mtt_material_code, mtt_remark, mtt_price, mtt_status, created_by, created_date) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("sssssdsss", $tenderYear, $tenderNo, $supplierCode, $materialCode, $remark, $price, $status, $createdBy, $dateNow);
    return $stmt->execute();
}

/**
 * Get materials by category for modal display
 * Retrieves active materials in a category with existing prices (if any)
 * @param mysqli $con Database connection
 * @param string $categoryCode Category code (V, S, F, D, O, Y, C, E, R, H, M, P, I, B)
 * @param string $supplierCode Supplier code
 * @param string $tenderNo Tender number
 * @param string|null $tenderYear Tender year (for junction with prices)
 * @return mysqli_result|false
 */
function getMaterialsByCategory($con, $categoryCode, $supplierCode, $tenderNo, $tenderYear = null)
{
    $query = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE, MMC_CAT_CODE,
                     mms_tenderprice_transactions.mtt_price AS MMC_PRICE,
                     mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
              FROM mms_material_catalogue
              LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
                AND mtt_supplier_code = ?
                AND mtt_tender_no = ?
              WHERE MMC_CAT_CODE = ? AND MMC_STATUS = 'A' 
              ORDER BY MMC_DESCRIPTION ASC";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("sss", $supplierCode, $tenderNo, $categoryCode);
    $stmt->execute();
    
    return $stmt->get_result();
}

/**
 * Get all saved items for preview modal
 * @param mysqli $con Database connection
 * @param string $supplierCode Supplier code
 * @return array Saved items
 */
function getAllSavedItems($con, $supplierCode)
{
    $query = "SELECT mtt_material_code, MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_CAT_CODE, 
                     mtt_price, mtt_remark,
                     (SELECT mtc_description FROM mms_tendermaterial_categories WHERE mtc_cat_code = MMC_CAT_CODE) AS CATDESC
              FROM mms_tenderprice_transactions
              LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code
              WHERE mms_tenderprice_transactions.mtt_supplier_code = ?
              ORDER BY CATDESC, MMC_DESCRIPTION ASC";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $supplierCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    return $items;
}

/**
 * Get items by category for preview filters
 * @param mysqli $con Database connection
 * @param string $categoryCode Category code
 * @param string $supplierCode Supplier code
 * @param string $tenderType Tender type for filtering
 * @return array Items in category
 */
function getItemsByCategory($con, $categoryCode, $supplierCode, $tenderType)
{
    $query = "SELECT mtt_material_code, MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC,
                     mtt_price, mtt_remark
              FROM mms_tenderprice_transactions
              LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code
              WHERE mms_tenderprice_transactions.mtt_supplier_code = ? AND MMC_CAT_CODE = ?
              ORDER BY MMC_DESCRIPTION ASC";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("ss", $supplierCode, $categoryCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    return $items;
}
?>
