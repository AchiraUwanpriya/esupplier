<?php
session_start();
$__root = __DIR__ . '/../../';
require_once $__root . 'backend/common/config.php';
require_once $__root . 'backend/supplier/dashboard_queries.php';

// Ensure supplier is logged in
if (!isset($_SESSION['sup_code'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$supplier_code = $_SESSION['sup_code'];
$user_category = $_SESSION['sup_category'] ?? '';

if (!$user_category) {
    echo json_encode([]);
    exit;
}

$dashboardQueries = new DashboardQueries();
$items = $dashboardQueries->getSavedTenderItems($supplier_code, $user_category);

header('Content-Type: application/json');
echo json_encode($items);
?>
