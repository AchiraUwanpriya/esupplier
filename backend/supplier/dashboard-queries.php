<?php
/**
 * Dashboard Queries - Supplier Module
 * Contains all database queries related to tender items and prices
 */

/**
 * Get tender details by category
 * Returns active tender number and year for a given category
 */
function getTenderByCategory($con, $category) {
    if ($category === 'RI') {
        $tnStmt = mysqli_prepare($con, "SELECT mtd_tender_no, mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1");
    } else {
        $tnStmt = mysqli_prepare($con, "SELECT mtd_tender_no, mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = ? LIMIT 1");
        mysqli_stmt_bind_param($tnStmt, 's', $category);
    }
    
    mysqli_stmt_execute($tnStmt);
    mysqli_stmt_bind_result($tnStmt, $tenderNo, $tenderYear);
    $fetched = mysqli_stmt_fetch($tnStmt);
    mysqli_stmt_close($tnStmt);
    
    if (!$fetched) {
        return null;
    }
    
    return ['tenderno' => $tenderNo, 'tenderyear' => $tenderYear];
}

/**
 * Get existing tender price transaction
 */
function getExistingPrice($con, $tenderYear, $tenderNo, $supplierCode, $materialCode) {
    $selectStmt = mysqli_prepare($con, "SELECT mtt_price, mtt_remark FROM mms_tenderprice_transactions 
                                        WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? 
                                        AND mtt_material_code=? AND mtt_status='A' LIMIT 1");
    mysqli_stmt_bind_param($selectStmt, 'ssss', $tenderYear, $tenderNo, $supplierCode, $materialCode);
    mysqli_stmt_execute($selectStmt);
    mysqli_stmt_store_result($selectStmt);
    
    $result = null;
    if (mysqli_stmt_num_rows($selectStmt) > 0) {
        mysqli_stmt_bind_result($selectStmt, $existingPrice, $existingRemark);
        mysqli_stmt_fetch($selectStmt);
        $result = ['price' => $existingPrice, 'remark' => $existingRemark];
    }
    
    mysqli_stmt_close($selectStmt);
    return $result;
}

/**
 * Delete tender price transaction
 */
function deletePriceTransaction($con, $supplierCode, $materialCode, $tenderNo) {
    $deleteStmt = mysqli_prepare($con, "DELETE FROM mms_tenderprice_transactions 
                                       WHERE mtt_supplier_code=? AND mtt_material_code=? 
                                       AND mtt_status='A' AND mtt_tender_no=?");
    mysqli_stmt_bind_param($deleteStmt, 'sss', $supplierCode, $materialCode, $tenderNo);
    $result = mysqli_stmt_execute($deleteStmt);
    mysqli_stmt_close($deleteStmt);
    
    return $result;
}

/**
 * Update tender price transaction
 */
function updatePriceTransaction($con, $tenderYear, $tenderNo, $supplierCode, $materialCode, $remark, $price, $updatedBy, $updatedDate) {
    $updateStmt = mysqli_prepare($con, "UPDATE mms_tenderprice_transactions 
                                       SET mtt_remark=?, mtt_price=?, updated_by=?, updated_date=? 
                                       WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? 
                                       AND mtt_material_code=? AND mtt_status='A'");
    mysqli_stmt_bind_param($updateStmt, 'sdssssss', $remark, $price, $updatedBy, $updatedDate, 
                           $tenderYear, $tenderNo, $supplierCode, $materialCode);
    $result = mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);
    
    return $result;
}

/**
 * Insert tender price transaction
 */
function insertPriceTransaction($con, $tenderYear, $tenderNo, $supplierCode, $materialCode, $remark, $price, $status, $createdBy, $createdDate) {
    $insertStmt = mysqli_prepare($con, "INSERT INTO mms_tenderprice_transactions 
                                       (mtt_year, mtt_tender_no, mtt_supplier_code, mtt_material_code, mtt_remark, 
                                        mtt_price, mtt_status, created_by, created_date) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($insertStmt, 'sssssdsss', $tenderYear, $tenderNo, $supplierCode, $materialCode, 
                           $remark, $price, $status, $createdBy, $createdDate);
    $result = mysqli_stmt_execute($insertStmt);
    mysqli_stmt_close($insertStmt);
    
    return $result;
}

/**
 * Get materials for a specific category
 */
function getMaterialsByCategory($con, $categoryCode, $supplierCode, $tenderNo, $tenderType) {
    $typeCondition = ($tenderType === 'RI') ? "AND mtd_type IS NULL" : "AND mtd_type = '$tenderType'";
    
    $tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE, MMC_CAT_CODE,
             mms_tenderprice_transactions.mtt_price AS MMC_PRICE,
             mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
             FROM mms_material_catalogue
             LEFT JOIN mms_tenderprice_transactions 
                ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
                AND mtt_supplier_code = '$supplierCode'
                AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details 
                                     WHERE mtd_status = 'A' $typeCondition)
             WHERE MMC_CAT_CODE = '$categoryCode' AND MMC_STATUS = 'A' 
             ORDER BY MMC_DESCRIPTION ASC";
    
    $stmt = mysqli_query($con, $tsql);
    return $stmt;
}

/**
 * Get all saved items for preview modal
 */
function getAllSavedItems($con, $supplierCode) {
    $tsql = "SELECT MMC_DESCRIPTION, mtt_price, 
             (SELECT CASE 
                WHEN MMC_CAT_CODE='V' THEN 'Vegetables'
                WHEN MMC_CAT_CODE='S' THEN 'Spices'
                WHEN MMC_CAT_CODE='F' THEN 'Fish'
                WHEN MMC_CAT_CODE='D' THEN 'Dry Fish'
                WHEN MMC_CAT_CODE='O' THEN 'Coconut Oil'
                WHEN MMC_CAT_CODE='Y' THEN 'Dry Items'
                WHEN MMC_CAT_CODE='C' THEN 'Coconut'
                WHEN MMC_CAT_CODE='E' THEN 'Eggs'
                WHEN MMC_CAT_CODE='R' THEN 'Rice'
                WHEN MMC_CAT_CODE='H' THEN 'Meat'
                WHEN MMC_CAT_CODE='M' THEN 'Miscellaneous'
                WHEN MMC_CAT_CODE='P' THEN 'PVC Items'
                WHEN MMC_CAT_CODE='I' THEN 'Medicine'
                WHEN MMC_CAT_CODE='B' THEN 'Cables'
                ELSE 'Other' END) AS CategoryName
             FROM mms_tenderprice_transactions
             LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no
             LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code
             WHERE mms_tenderprice_transactions.mtt_supplier_code = '$supplierCode' 
             AND mms_tender_details.mtd_status = 'A'
             AND mms_tenderprice_transactions.mtt_status = 'A'";
    
    $stmt = mysqli_query($con, $tsql);
    return $stmt;
}

/**
 * Get items by category code for inventory display
 */
function getItemsByCategory($con, $categoryCode, $supplierCode, $tenderType) {
    $typeCondition = ($tenderType === 'RI') ? "AND mtd_type IS NULL" : "AND mtd_type = '$tenderType'";
    
    $tsql = "SELECT mms_material_catalogue.MMC_DESCRIPTION, mms_tenderprice_transactions.mtt_price
             FROM mms_material_catalogue
             LEFT JOIN mms_tenderprice_transactions 
                ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
                AND mtt_supplier_code = '$supplierCode'
                AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details 
                                     WHERE mtd_status = 'A' $typeCondition)
             WHERE mms_material_catalogue.MMC_CAT_CODE = '$categoryCode' 
             AND mms_material_catalogue.MMC_STATUS = 'A'
             ORDER BY mms_material_catalogue.MMC_DESCRIPTION ASC";
    
    $stmt = mysqli_query($con, $tsql);
    return $stmt;
}
?>
