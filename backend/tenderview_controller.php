<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: ../admin.php');
    exit();
}

require_once __DIR__ . '/common/config.php';
include_once __DIR__ . '/queries/tenderview_queries.php';

$entry = $_SESSION['entry'];

$supcodevalue = null; // Defined for compatibility
$tenders = [];

$tsql = get_tenders_sql();
$stmt = mysqli_query($con, $tsql);

if ($stmt === false) {
    echo "Error in query";
    die(print_r(mysqli_error($con), true));
}

while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
    $tender = $row;
    
    $tsql2 = get_tender_suppliers_sql($row['mtd_tender_no']);
    $stmt2 = mysqli_query($con, $tsql2);
    
    if ($stmt2 === false) {
        echo "Error in query";
        die(print_r(mysqli_error($con), true));
    }

    $suppilers = [];
    while ($row1 = mysqli_fetch_array($stmt2, MYSQLI_ASSOC)) {
        $suppiler = $row1;
        $items = [];
        
        $categories = ['V', 'S', 'F', 'D', 'O', 'Y', 'C', 'E', 'R', 'H', 'M', 'P', 'I', 'B'];
        foreach ($categories as $cat) {
            $tsql3 = get_tender_items_by_category_sql($row1['msd_supplier_code'], $row['mtd_tender_no'], $cat);
            $stmt3 = mysqli_query($con, $tsql3);
            if ($stmt3 === false) {
                echo "Error in query";
                die(print_r(mysqli_error($con), true));
            }
            $catItems = [];
            while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
                $catItems[] = $item;
            }
            $items[$cat] = $catItems;
        }
        
        $suppiler['items'] = $items;
        $suppilers[] = $suppiler;
    }
    $tender['suppilers'] = $suppilers;
    $tenders[] = $tender;
}
?>
