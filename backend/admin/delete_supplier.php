<?php
// backend/admin/delete_supplier.php - Refactored
require_once __DIR__ . '/../common/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supplierCode'])) {
    $supplierCode = $_POST['supplierCode'];

    // Perform the deletion query
    $deleteQuery = "DELETE FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
    $stmt = mysqli_prepare($con, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "s", $supplierCode);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect back to the page
    header("Location: ../../Public/admin/allsuppliersview.php");
    exit();
} else {
    // Invalid request or missing parameters
    http_response_code(400);
}
?>
