<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

require_once __DIR__ . '/supplier_queries.php';

try {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    $supplierCode = isset($_POST['supplier_code']) ? trim($_POST['supplier_code']) : '';

    if ($supplierCode === '') {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Supplier code is required']);
        exit();
    }

    $supplierQueries = new SupplierQueries();

    if ($action === 'approve') {
        $supplierData = [
            'supplier_code' => $supplierCode,
            'supplier_name' => isset($_POST['supplier_name']) ? trim($_POST['supplier_name']) : '',
            'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
            'mobile' => isset($_POST['mobile']) ? trim($_POST['mobile']) : '',
            'category' => isset($_POST['category']) ? trim($_POST['category']) : '',
            'category_description' => isset($_POST['category_description']) ? trim($_POST['category_description']) : '',
            'address' => isset($_POST['address']) ? trim($_POST['address']) : ''
        ];

        $result = $supplierQueries->approvePendingSupplier($supplierData);
        echo json_encode($result);
        exit();
    }

    if ($action === 'delete') {
        $result = $supplierQueries->deletePendingSupplier($supplierCode);
        echo json_encode($result);
        exit();
    }

    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error while processing supplier action',
        'error' => $exception->getMessage()
    ]);
}
