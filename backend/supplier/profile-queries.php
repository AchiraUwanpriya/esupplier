<?php
/**
 * Profile Page Database Queries
 * All queries use prepared statements for security
 */

/**
 * Get supplier details by supplier code
 * @param mysqli $con Database connection
 * @param string $supplierCode Supplier code
 * @return mysqli_result|false
 */
function getSupplierDetails($con, $supplierCode)
{
    $query = "SELECT * FROM mms_suppliers_details WHERE msd_supplier_code = ?";
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("s", $supplierCode);
    $stmt->execute();
    
    return $stmt->get_result();
}

/**
 * Get all bank details for a supplier
 * @param mysqli $con Database connection
 * @param string $supplierCode Supplier code
 * @return array Bank details array
 */
function getSupplierBankDetails($con, $supplierCode)
{
    $query = "SELECT MMSSB.*, 
                     MMSDB.MBD_BANK_NAME, 
                     MMSDB2.MBD_BANK_NAME AS BRANCH_NAME 
              FROM mms_supplier_banks MMSSB 
              LEFT JOIN mms_bank_details MMSDB ON MMSDB.MBD_CHILD_KEY = MMSSB.MSB_MAIN_BANK_CODE 
              LEFT JOIN mms_bank_details MMSDB2 ON MMSDB2.MBD_CHILD_KEY = MMSSB.MSB_CHILD_KEY
              WHERE MMSSB.MSB_SUPPLIER_CODE = ?";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $supplierCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $dataList = [];
    while ($row = $result->fetch_assoc()) {
        $dataList[] = $row;
    }
    
    return $dataList;
}

/**
 * Get all main banks (parent banks)
 * @param mysqli $con Database connection
 * @return array Banks list
 */
function getMainBanks($con)
{
    $query = "SELECT MBD_CHILD_KEY, MBD_BANK_NAME 
              FROM mms_bank_details
              WHERE MBD_BANK_TYPE = 'L' AND MBD_STATUS = 'A' AND MBD_PARENT_KEY IS NULL
              ORDER BY MBD_BANK_NAME";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $banks = [];
    while ($row = $result->fetch_assoc()) {
        $banks[] = $row;
    }
    
    return $banks;
}

/**
 * Get branches for a main bank
 * @param mysqli $con Database connection
 * @param string $mainBankCode Main bank code
 * @return array Branches list
 */
function getBankBranches($con, $mainBankCode)
{
    $query = "SELECT MBD_CHILD_KEY, MBD_BANK_NAME
              FROM mms_bank_details 
              WHERE MBD_BANK_TYPE = 'L' AND MBD_STATUS = 'A' AND MBD_PARENT_KEY = ?
              ORDER BY MBD_BANK_NAME";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $mainBankCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $branches = [];
    while ($row = $result->fetch_assoc()) {
        $branches[] = $row;
    }
    
    return $branches;
}

/**
 * Insert or update supplier bank details
 * @param mysqli $con Database connection
 * @param array $data Bank data array
 * @return bool Success status
 */
function saveSupplierBank($con, $data)
{
    $supplierCode = $data['supplierCode'];
    $supbankid = $data['supbankid'];
    $mainbank = $data['mainbank'];
    $branch = $data['branch'];
    $accnumber = $data['accnumber'];
    $acctype = $data['acctype'];
    $bankcode = $data['bankcode'];
    
    if ($supbankid === "" || $supbankid === "0" || empty($supbankid)) {
        // INSERT new bank record
        $query = "INSERT INTO mms_supplier_banks 
                  (MSB_SUPPLIER_CODE, MSB_MAIN_BANK_CODE, MSB_BANK_CODE, MSB_CHILD_KEY, MSB_ACCOUNT_NO, MSB_ACCOUNT_TYPE, CREATED_BY, CREATED_DATE) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $con->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("sssssss", $supplierCode, $mainbank, $bankcode, $branch, $accnumber, $acctype, $supplierCode);
        return $stmt->execute();
    } else {
        // UPDATE existing bank record
        $query = "UPDATE mms_supplier_banks 
                  SET MSB_MAIN_BANK_CODE = ?, 
                      MSB_BANK_CODE = ?, 
                      MSB_CHILD_KEY = ?, 
                      MSB_ACCOUNT_NO = ?, 
                      MSB_ACCOUNT_TYPE = ?, 
                      UPDATED_BY = ?, 
                      UPDATED_DATE = NOW()
                  WHERE MSB_SUPPLIER_BANK_ID = ?";
        
        $stmt = $con->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("ssssssi", $mainbank, $bankcode, $branch, $accnumber, $acctype, $supplierCode, $supbankid);
        return $stmt->execute();
    }
}

/**
 * Get tax details for supplier
 * @param mysqli $con Database connection
 * @param string $supplierCode Supplier code
 * @return array Tax details array
 */
function getTaxDetails($con, $supplierCode)
{
    $query = "SELECT * FROM mms_tax_details WHERE msd_supplier_code = ? LIMIT 1";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $supplierCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $taxdetails = [];
    if ($result && $result->num_rows > 0) {
        $taxdetails = $result->fetch_assoc();
    }
    
    return $taxdetails;
}

/**
 * Insert or update tax details
 * @param mysqli $con Database connection
 * @param array $data Tax data array
 * @return bool Success status
 */
function saveTaxDetails($con, $data)
{
    $supplierCode = $data['supplierCode'];
    $msdid = $data['msdid'];
    $VAT = $data['VAT'];
    $SVAT = $data['SVAT'];
    
    if ($msdid === "" || $msdid === "0" || empty($msdid)) {
        // INSERT new tax record
        $query = "INSERT INTO mms_tax_details 
                  (msd_supplier_code, msd_vat, msd_svat, created_by, created_date) 
                  VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $con->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("ssss", $supplierCode, $VAT, $SVAT, $supplierCode);
        return $stmt->execute();
    } else {
        // UPDATE existing tax record
        $query = "UPDATE mms_tax_details 
                  SET msd_vat = ?, 
                      msd_svat = ?, 
                      updated_by = ?, 
                      updated_date = NOW()
                  WHERE msd_id = ?";
        
        $stmt = $con->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("sssi", $VAT, $SVAT, $supplierCode, $msdid);
        return $stmt->execute();
    }
}

/**
 * Get supplier material categories
 * @param mysqli $con Database connection
 * @param string $supplierCode Supplier code
 * @return array Categories array
 */
function getSupplierMaterialCategories($con, $supplierCode)
{
    $query = "SELECT DISTINCT MMC_CAT_CODE,
                     (SELECT mtc_description FROM mms_tendermaterial_categories 
                      WHERE mtc_cat_code = MMC_CAT_CODE) AS CATDESC 
              FROM mms_tenderprice_transactions 
              LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
              WHERE mms_tenderprice_transactions.mtt_supplier_code = ?
              ORDER BY MMC_CAT_CODE";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $supplierCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

/**
 * Get supplier attachments
 * @param mysqli $con Database connection
 * @param string $supplierCode Supplier code
 * @return array Attachments array
 */
function getSupplierAttachments($con, $supplierCode)
{
    $query = "SELECT msd_serial_no, msd_file_name, msd_file_path, msd_status 
              FROM mms_supplier_attachments 
              WHERE msd_sup_code = ?
              ORDER BY msd_serial_no DESC";
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $supplierCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $attachments = [];
    while ($row = $result->fetch_assoc()) {
        $attachments[] = $row;
    }
    
    return $attachments;
}

/**
 * Update supplier details
 * @param mysqli $con Database connection
 * @param array $data Supplier data array
 * @return bool Success status
 */
function updateSupplierDetails($con, $data)
{
    $supplierCode = $data['supplierCode'];
    $bsnature = $data['bsnature'];
    $country = $data['country'];
    $address = $data['address'];
    $officeaddress = $data['officeaddress'];
    $operationaddress = $data['operationaddress'];
    $telnumber = $data['telnumber'];
    $postalCode = $data['postalCode'];
    $fax = $data['fax'];
    $emailad = $data['emailad'];
    $web = $data['web'];
    $contactperson = $data['contactperson'];
    $agent = $data['agent'];
    
    $query = "UPDATE mms_suppliers_details 
              SET msd_business_nature = ?, 
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
    
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("sssssssssss",
        $bsnature,
        $address,
        $officeaddress,
        $operationaddress,
        $postalCode,
        $telnumber,
        $fax,
        $emailad,
        $web,
        $contactperson,
        $agent,
        $supplierCode
    );
    
    return $stmt->execute();
}

/**
 * Get helper function for safely retrieving array values
 * @param array $array Target array
 * @param string $key Key to retrieve
 * @param string $default Default value if key doesn't exist
 * @return string Value from array or default
 */
function getvalue($array, $key, $default = '')
{
    return isset($array[$key]) && !empty($array[$key]) ? htmlspecialchars($array[$key]) : $default;
}
?>
