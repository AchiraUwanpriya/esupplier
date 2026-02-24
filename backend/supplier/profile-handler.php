<?php
/**
 * Profile Page Request Handler
 * Handles all POST requests for supplier profile updates
 */

require_once(__DIR__ . '/profile-queries.php');

/**
 * Handle form submissions
 */
function handleProfileRequests()
{
    global $con;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }
    
    // Handle supplier details update
    if (isset($_POST['updateSupBtn'])) {
        handleSupplierDetailsUpdate();
    }
    
    // Handle bank details update
    if (isset($_POST['updateBankBtn'])) {
        handleBankDetailsUpdate();
    }
    
    // Handle tax details update
    if (isset($_POST['updateTaxBtn'])) {
        handleTaxDetailsUpdate();
    }
}

/**
 * Handle supplier details update
 */
function handleSupplierDetailsUpdate()
{
    global $con;
    
    $supplierCode = $_SESSION['sup_code'] ?? '';
    
    if (empty($supplierCode)) {
        sendAlert("Error: Invalid supplier code");
        return;
    }
    
    // Collect and sanitize form data
    $data = [
        'supplierCode' => $supplierCode,
        'bsnature' => $_POST['bsnature'] ?? '',
        'country' => $_POST['country'] ?? 'Sri Lanka',
        'address' => $_POST['address'] ?? '',
        'officeaddress' => $_POST['officeaddress'] ?? '',
        'operationaddress' => $_POST['operationaddress'] ?? '',
        'telnumber' => $_POST['telnumber'] ?? '',
        'postalCode' => $_POST['postalCode'] ?? '',
        'fax' => $_POST['fax'] ?? '',
        'emailad' => $_POST['emailad'] ?? '',
        'web' => $_POST['web'] ?? '',
        'contactperson' => $_POST['contactperson'] ?? '',
        'agent' => $_POST['agent'] ?? ''
    ];
    
    if (updateSupplierDetails($con, $data)) {
        sendAlert("Profile successfully updated!", true);
    } else {
        sendAlert("Error updating profile. Please try again.");
    }
}

/**
 * Handle bank details update
 */
function handleBankDetailsUpdate()
{
    global $con;
    
    $supplierCode = $_SESSION['sup_code'] ?? '';
    
    if (empty($supplierCode)) {
        sendAlert("Error: Invalid supplier code");
        return;
    }
    
    // Collect and sanitize form data
    $data = [
        'supplierCode' => $supplierCode,
        'supbankid' => $_POST['supbankid'] ?? '',
        'mainbank' => $_POST['mainbank'] ?? '',
        'branch' => $_POST['branch'] ?? '',
        'accnumber' => $_POST['accnumber'] ?? '',
        'acctype' => $_POST['acctype'] ?? '',
        'bankcode' => $_POST['bankcode'] ?? ''
    ];
    
    // Validate required fields
    if (empty($data['mainbank']) || empty($data['accnumber']) || empty($data['acctype'])) {
        sendAlert("Please fill all required fields");
        return;
    }
    
    if (saveSupplierBank($con, $data)) {
        sendAlert("Bank details successfully saved!", true);
    } else {
        sendAlert("Error saving bank details. Please try again.");
    }
}

/**
 * Handle tax details update
 */
function handleTaxDetailsUpdate()
{
    global $con;
    
    $supplierCode = $_SESSION['sup_code'] ?? '';
    
    if (empty($supplierCode)) {
        sendAlert("Error: Invalid supplier code");
        return;
    }
    
    // Collect and sanitize form data
    $data = [
        'supplierCode' => $supplierCode,
        'msdid' => $_POST['msdid'] ?? '',
        'VAT' => $_POST['VAT'] ?? '',
        'SVAT' => $_POST['SVAT'] ?? ''
    ];
    
    // Validate required fields
    if (empty($data['VAT']) || empty($data['SVAT'])) {
        sendAlert("Please fill all required fields");
        return;
    }
    
    if (saveTaxDetails($con, $data)) {
        sendAlert("Tax details successfully saved!", true);
    } else {
        sendAlert("Error saving tax details. Please try again.");
    }
}

/**
 * Send alert and redirect if needed
 * @param string $message Alert message
 * @param bool $redirect Whether to redirect back to profile.php
 */
function sendAlert($message, $redirect = false)
{
    echo '<script language="javascript">';
    echo 'alert("' . addslashes($message) . '");';
    if ($redirect) {
        echo 'location.href="profile.php";';
    }
    echo '</script>';
}

// Execute handler
handleProfileRequests();
?>
