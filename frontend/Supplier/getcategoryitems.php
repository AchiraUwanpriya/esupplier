<?php
session_start();
$__root = __DIR__ . '/../../';
require_once $__root . 'backend/common/config.php';
require_once $__root . 'backend/supplier/dashboard_queries.php';

if (!isset($_SESSION['sup_code'])) {
    http_response_code(403);
    exit;
}

$supplier_code = $_SESSION['sup_code'];
$user_cat = $_SESSION['sup_category'] ?? '';
$cat_code = $_GET['cat_code'] ?? '';

if (!$cat_code) {
    echo json_encode([]);
    exit;
}

$dashboardQueries = new DashboardQueries();
$items = $dashboardQueries->getSavedCategoryItems($supplier_code, $user_cat, $cat_code);

header('Content-Type: application/json');
echo json_encode($items);
?>
