<?php
// SuppplierDone.php - Refactored for Public/Supplier/
session_start();
$__root = __DIR__ . '/../../';
require_once $__root . 'backend/common/config.php';
require_once $__root . 'backend/supplier/dashboard_queries.php';

date_default_timezone_set('Asia/Colombo');
$dashboardQueries = new DashboardQueries();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suppliercode = $_SESSION['sup_code'];
    $user_category = $_SESSION['sup_category'] ?? '';

    // 1. Check if prices are saved
    if (!$dashboardQueries->checkTenderPrices($suppliercode, $user_category)) {
        echo "Please input and save item values before submitting the tender.";
        exit;
    }

    // 2. Submit Tender
    $success = $dashboardQueries->submitTender($suppliercode, $user_category);

    if ($success) {
        // Send SMS notification
        $mobile = $dashboardQueries->getSupplierMobile($suppliercode);
        if ($mobile) {
            $activeTender = $dashboardQueries->getActiveTender($user_category);
            $tenderNo = $activeTender['mtd_tender_no'] ?? 'N/A';
            $message = "$tenderNo Tender submitted successfully.";
            sendMessage($mobile, $message, "Esupplier");
        }
        echo "success";
    } else {
        echo "Error occurred while submitting tender.";
    }
}

function sendMessage($mobileNo = '', $msg = '', $subject = '') {
    $ch = curl_init();
    $uri = "https://esystems.cdl.lk/apidock/api/SMS/SendMsgTxt?mobileNo=" . urlencode($mobileNo) . "&msg=" . urlencode($msg) . "&subject=" . urlencode($subject);
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}
?>
