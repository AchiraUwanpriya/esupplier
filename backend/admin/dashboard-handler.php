<?php
/**
 * Admin Dashboard Request Handler
 * Handles POST requests for tender price submissions
 */

require_once(__DIR__ . '/dashboard-queries.php');

/**
 * Handle tender price submission
 */
function handleTenderPriceSubmission()
{
    global $con;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['insert'])) {
        return;
    }
    
    // Validate category
    $allowed = ['vegitables', 'spices', 'fish', 'dfish', 'rice', 'pvcs', 'med', 'cab'];
    if (!in_array($_POST['insert'], $allowed, true)) {
        return;
    }
    
    // Get user category
    $userCategory = $_SESSION['sup_category'] ?? '';
    if (empty($userCategory)) {
        sendResponse(['status' => 'error', 'message' => 'No user category']);
        return;
    }
    
    // Get active tender for user's category
    $tender = getTenderByCategory($con, $userCategory);
    if (empty($tender)) {
        sendResponse(['status' => 'error', 'message' => 'No active tender for your category']);
        return;
    }
    
    $tenderNo = $tender['tenderNo'];
    $tenderYear = $tender['tenderYear'];
    $supplierCode = $_SESSION['sup_code'] ?? '';
    $success = true;
    
    // Process each row
    $rowCount = isset($_POST['MMC_DESCRIPTION']) ? count($_POST['MMC_DESCRIPTION']) : 0;
    
    for ($x = 0; $x < $rowCount; $x++) {
        $materialCode = $_POST['MMC_MATERIAL_CODE'][$x] ?? '';
        if (empty($materialCode)) {
            continue;
        }
        
        $remark = (isset($_POST['MMC_REMARK'][$x]) && $_POST['MMC_REMARK'][$x] !== '') ? $_POST['MMC_REMARK'][$x] : null;
        $price = (isset($_POST['MMC_PRICE'][$x]) && $_POST['MMC_PRICE'][$x] !== '') ? $_POST['MMC_PRICE'][$x] : null;
        
        // Handle deletion if price is empty
        if ($price === null) {
            deletePriceTransaction($con, $supplierCode, $materialCode, $tenderNo);
            continue;
        }
        
        // Check if record exists
        $existing = getExistingPrice($con, $tenderYear, $tenderNo, $supplierCode, $materialCode);
        
        // Skip if no change
        if (!empty($existing) && 
            (string)$existing['price'] === (string)$price && 
            (string)$existing['remark'] === (string)$remark) {
            continue;
        }
        
        // Update or insert
        if (!empty($existing)) {
            $success = updatePriceTransaction($con, $remark, $price, $supplierCode, $tenderYear, $tenderNo, $supplierCode, $materialCode) && $success;
        } else {
            $success = insertPriceTransaction($con, $tenderYear, $tenderNo, $supplierCode, $materialCode, $remark, $price, $supplierCode) && $success;
        }
    }
    
    mysqli_close($con);
    sendResponse(['status' => $success ? 'success' : 'error']);
}

/**
 * Send JSON or alert response
 * @param array $response Response data
 */
function sendResponse($response)
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $message = $response['status'] === 'success' ? 'Data saved successfully!' : 'Error occurred while saving the data!';
        echo "<script>alert('" . addslashes($message) . "');</script>";
    }
    exit;
}

// Execute handler
handleTenderPriceSubmission();
?>
