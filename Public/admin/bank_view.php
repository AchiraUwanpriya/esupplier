<?php
// Public/admin/bank_view.php - Refactored
require_once '../../backend/common/config.php';

// Select all images from database and display them in HTML <table>.
// NOTE: This logic seems to have a hardcoded supplier code from original file.
$supplier_code = $_GET['msd_supplier_code'] ?? '1661824373';

$sql = "SELECT * FROM mms_suppliers_details WHERE msd_supplier_code = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $supplier_code);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($res && mysqli_num_rows($res) > 0) {
    while ($images = mysqli_fetch_assoc($res)) {  ?>
        <div class="alb">
            <button>
                <img src="../../bank_details/<?= htmlspecialchars($images['image_url']) ?>">
            </button>
        </div>
<?php 
    }
} else {
    echo "No bank details found.";
}
?>
