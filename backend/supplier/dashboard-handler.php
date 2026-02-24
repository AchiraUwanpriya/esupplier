<?php
/**
 * Dashboard Handler - Supplier Module
 * Handles POST requests for tender price updates
 */

session_start();
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/dashboard-queries.php');

/**
 * Handle tender price submission from dashboard
 */
function handleTenderPriceSubmission() {
    global $con;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }
    
    if (!isset($_POST['insert'])) {
        return false;
    }
    
    $allowed = ['vegitables', 'spices', 'fish', 'dfish', 'rice', 'pvcs', 'med', 'cab'];
    if (!in_array($_POST['insert'], $allowed, true)) {
        return false;
    }
    
    // Validate user
    if (!isset($_SESSION['sup_code'])) {
        $response = ['status' => 'error', 'message' => 'User not authenticated'];
        sendResponse($response);
        return false;
    }
    
    $user_category = isset($_SESSION['sup_category']) ? $_SESSION['sup_category'] : '';
    if ($user_category === '') {
        $response = ['status' => 'error', 'message' => 'No user category'];
        sendResponse($response);
        return false;
    }
    
    // Get tender details
    $tenderData = getTenderByCategory($con, $user_category);
    if (!$tenderData) {
        $response = ['status' => 'error', 'message' => 'No active tender for your category'];
        sendResponse($response);
        return false;
    }
    
    $tenderNo = $tenderData['tenderno'];
    $tenderYear = $tenderData['tenderyear'];
    $suppliercode = $_SESSION['sup_code'];
    $sup_code = $suppliercode;
    $date_now = date('Y-m-d g:i A');
    
    // Process rows
    $rowCount = isset($_POST['MMC_DESCRIPTION']) ? count($_POST['MMC_DESCRIPTION']) : 0;
    $success = true;
    
    for ($x = 0; $x < $rowCount; $x++) {
        $MMC_DESCRIPTION = $_POST['MMC_DESCRIPTION'][$x] ?? '';
        $MMC_UNIT = $_POST['MMC_UNIT'][$x] ?? '';
        $MMC_REMARK = (isset($_POST['MMC_REMARK'][$x]) && $_POST['MMC_REMARK'][$x] !== '') ? $_POST['MMC_REMARK'][$x] : null;
        $MMC_PRICE = (isset($_POST['MMC_PRICE'][$x]) && $_POST['MMC_PRICE'][$x] !== '') ? $_POST['MMC_PRICE'][$x] : null;
        $MMC_MATERIAL_CODE = $_POST['MMC_MATERIAL_CODE'][$x] ?? '';
        $MMC_CAT_CODE = $_POST['MMC_CAT_CODE'][$x] ?? '';
        
        // Skip malformed rows
        if ($MMC_MATERIAL_CODE === '') {
            continue;
        }
        
        // Handle row deletion (empty price)
        if ($MMC_PRICE === null) {
            if (!deletePriceTransaction($con, $suppliercode, $MMC_MATERIAL_CODE, $tenderNo)) {
                $success = false;
            }
            continue;
        }
        
        // Check if row already exists
        $existingData = getExistingPrice($con, $tenderYear, $tenderNo, $suppliercode, $MMC_MATERIAL_CODE);
        
        // Skip if no change
        if ($existingData !== null) {
            if ((string)$existingData['price'] === (string)$MMC_PRICE && 
                (string)$existingData['remark'] === (string)$MMC_REMARK) {
                continue;
            }
            
            // Update existing record
            if (!updatePriceTransaction($con, $tenderYear, $tenderNo, $suppliercode, $MMC_MATERIAL_CODE, 
                                       $MMC_REMARK, $MMC_PRICE, $sup_code, $date_now)) {
                $success = false;
            }
        } else {
            // Insert new record
            $status = 'A';
            if (!insertPriceTransaction($con, $tenderYear, $tenderNo, $suppliercode, $MMC_MATERIAL_CODE, 
                                       $MMC_REMARK, $MMC_PRICE, $status, $sup_code, $date_now)) {
                $success = false;
            }
        }
    }
    
    mysqli_close($con);
    
    // Send response
    sendResponse(['status' => $success ? 'success' : 'error']);
    return $success;
}

/**
 * Send JSON response for AJAX requests, or alert for regular requests
 */
function sendResponse($response) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        if ($response['status'] === 'success') {
            echo "<script>alert('Data saved successfully!');</script>";
        } else {
            echo "<script>alert('" . ($response['message'] ?? 'Error occurred while saving the data!') . "');</script>";
        }
    }
    exit;
}

// Execute handler if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert'])) {
    handleTenderPriceSubmission();
}
?>
