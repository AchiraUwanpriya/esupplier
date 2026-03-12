<?php
session_start();
require_once __DIR__ . '/common/config.php';

// Admin authentication
if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: ../admin.php');
    exit();
}

$entry = $_SESSION['entry'];

// Include query functions
require_once __DIR__ . '/queries/material_catalogue_queries.php';
require_once __DIR__ . '/queries/addfood_queries.php';

// ============ PROCESS POST REQUESTS (INSERT/UPDATE) ============
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Handle UPDATE
    if (isset($_POST['updatebtn'])) {
        $sup_code = $_SESSION['mobile_number'];
        $date_now = date('Y-m-d');
        
        $result = updateMaterial(
            $con,
            $_POST['MaterialCode'],
            $_POST['Description'],
            $_POST['MaterialSpec'],
            $_POST['Unit'],
            $_POST['Status'],
            $sup_code,
            $date_now
        );
        
        $_SESSION['flash_message'] = $result['message'];
        $_SESSION['flash_type'] = $result['status'] ? 'success' : 'error';
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Handle INSERT
    if (isset($_POST['insertbtn'])) {
        $result = insertMaterial(
            $con,
            $_POST['MaterialCode'],
            $_POST['Description'],
            $_POST['MaterialSpec'],
            $_POST['Unit'],
            $_POST['CatCode']
        );
        
        $_SESSION['flash_message'] = $result['message'];
        $_SESSION['flash_type'] = $result['status'] ? 'success' : 'error';
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// ============ FETCH UNITS FROM DATABASE ============
$units_query = get_active_units_sql();
$units_result = mysqli_query($con, $units_query);
$units = [];
if ($units_result && mysqli_num_rows($units_result) > 0) {
    while ($row = mysqli_fetch_assoc($units_result)) {
        $units[] = $row;
    }
}

// ============ FIXED CATEGORY LIST ============
$categories = [
    ['code' => 'V', 'name' => 'VEGETABLE ITEMS'],
    ['code' => 'Y', 'name' => 'DRY ITEMS'],
    ['code' => 'P', 'name' => 'PVC ITEMS'],
    ['code' => 'S', 'name' => 'SPICES'],
    ['code' => 'R', 'name' => 'RICE'],
    ['code' => 'I', 'name' => 'MEDICINE ITEMS'],
    ['code' => 'F', 'name' => 'FISH'],
    ['code' => 'H', 'name' => 'MEAT'],
    ['code' => 'D', 'name' => 'DRY FISH'],
    ['code' => 'M', 'name' => 'MISCELLANEOUS ITEMS']
];

$materials_by_category = [];
foreach ($categories as $cat) {
    $code = $cat['code'];
    $mat_query = get_materials_by_category_sql($code);
    $mat_result = mysqli_query($con, $mat_query);
    
    $catItems = [];
    if($mat_result) {
        while ($row = mysqli_fetch_assoc($mat_result)) {
            $catItems[] = $row;
        }
    }
    $materials_by_category[$code] = $catItems;
}

// Icon mapping function
function getIcon($code) {
    $base_path = '../../static/img/';
    switch ($code) {
        case 'V': return $base_path . 'vegetable.png';
        case 'S': return $base_path . 'spice.png';
        case 'F': return $base_path . 'fish.png';
        case 'D': return $base_path . 'dried-fish.png';
        case 'Y': return $base_path . 'dried-item.png';
        case 'C': return $base_path . 'coconut.png';
        case 'O': return $base_path . 'coconut-oil.png';
        case 'R': return $base_path . 'rice.png';
        case 'H': return $base_path . 'chicken-leg.png';
        case 'M': return $base_path . 'gift-wrapping.png';
        case 'P': return $base_path . 'Pvc.png';
        case 'I': return $base_path . 'medicine.png';
        case 'E': return $base_path . 'eggs.png';
        case 'B': return $base_path . 'cables.png';
        default: return $base_path . '2.svg';
    }
}
?>
