<?php
// File: helper.php

if (!function_exists('getvalue')) {
    function getvalue($row, $key, $default = "") {
        return isset($row[$key]) ? $row[$key] : $default;
    }
}

if (!function_exists('getCategoryIdFromSupplierCode')) {
    function getCategoryIdFromSupplierCode($con, $supplier_code) {
        if (empty($supplier_code)) {
            return 1; // Default to Ration Items
        }
        
        // First, get the supplier's category name directly from the database
        $category_name = '';
        
        // Check pending details first
        $query = "SELECT msd_supply_category FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $supplier_code);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $category_name = $row['msd_supply_category'];
                echo "<!-- Debug: Found in pending: " . $category_name . " -->";
            }
            mysqli_stmt_close($stmt);
        }
        
        // If not found in pending, check approved suppliers
        if (empty($category_name)) {
            $query = "SELECT msd_supply_category FROM mms_suppliers_details WHERE msd_supplier_code = ?";
            $stmt = mysqli_prepare($con, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $supplier_code);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $category_name = $row['msd_supply_category'];
                    echo "<!-- Debug: Found in approved: " . $category_name . " -->";
                }
                mysqli_stmt_close($stmt);
            }
        }
        
        // Now map the category name to category_id
        if (!empty($category_name)) {
            // Query the mms_categories table to get the correct ID
            $query = "SELECT category_id FROM mms_categories WHERE category_name = ? LIMIT 1";
            $stmt = mysqli_prepare($con, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $category_name);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $category_id = $row['category_id'];
                    mysqli_stmt_close($stmt);
                    echo "<!-- Debug: Mapped to category_id: " . $category_id . " -->";
                    return $category_id;
                }
                mysqli_stmt_close($stmt);
            }
        }
        
        // If we couldn't find the category, log it and return default
        echo "<!-- Debug: Could not find category for: " . $category_name . " -->";
        return 1; // Default to Ration Items
    }
}

if (!function_exists('getSupplierCategoryName')) {
    function getSupplierCategoryName($con, $supplier_code) {
        if (empty($supplier_code)) {
            return '';
        }
        
        // Check pending details first
        $query = "SELECT msd_supply_category FROM mms_supplier_pending_details WHERE msd_supplier_code = ?";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $supplier_code);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $row['msd_supply_category'];
            }
            mysqli_stmt_close($stmt);
        }
        
        // Check approved suppliers
        $query = "SELECT msd_supply_category FROM mms_suppliers_details WHERE msd_supplier_code = ?";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $supplier_code);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $row['msd_supply_category'];
            }
            mysqli_stmt_close($stmt);
        }
        
        return '';
    }
}

if (!function_exists('getCategoryNameFromId')) {
    function getCategoryNameFromId($con, $category_id) {
        $query = "SELECT category_name FROM mms_categories WHERE category_id = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $category_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $row['category_name'];
            }
            mysqli_stmt_close($stmt);
        }
        return 'General Items';
    }
}
?>