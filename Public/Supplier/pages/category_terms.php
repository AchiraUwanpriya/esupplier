<?php
// File: public/supplier/pages/category_terms.php

// Include database connection and helper from backend
require_once dirname(__DIR__, 3) . '/backend/common/config.php';
require_once dirname(__DIR__, 3) . '/backend/common/helper.php';

// Get database connection
$con = Database::getInstance();

// Get supplier code from session
$supplier_code = isset($_SESSION['sup_code']) ? $_SESSION['sup_code'] : '';

// Get category ID from supplier code
$cat_id = Helper::getCategoryIdFromSupplierCode($con, $supplier_code);

// Get category name from ID
$category_name = Helper::getCategoryNameFromId($con, $cat_id);

// Query to get terms from database using the correct category_id
$terms_query = "SELECT ct_term FROM category_terms 
                WHERE ct_category_id = ? 
                AND ct_status = 'A' 
                ORDER BY ct_order ASC";

$stmt = $con->prepare($terms_query);
$stmt->bind_param("i", $cat_id);
$stmt->execute();
$terms_result = $stmt->get_result();
?>

<!-- TERMS & CONDITIONS CARD -->
<div class="terms-container">
    <!-- TERMS & CONDITIONS - Category Name -->
    <h2 class="fs-title terms-heading">TERMS & CONDITIONS - <?php echo htmlspecialchars($category_name); ?></h2>
    
    <!-- Terms list -->
    <ol class="terms-list">
        <?php 
        if ($terms_result && $terms_result->num_rows > 0) {
            while($term = $terms_result->fetch_assoc()) {
                echo '<li>' . htmlspecialchars($term['ct_term']) . '</li>';
            }
        } else {
            // If no terms found in database, show a message
            echo '<li>No specific terms and conditions found for this category. Please contact administrator.</li>';
        }
        ?>
    </ol>
    
    <!-- Checkbox line -->
    <div class="terms-checkbox">
        <input type="checkbox" id="agreeTerms" onchange="toggleNextButton()">
        <label for="agreeTerms">
            <strong>We agreed to abide by the terms & condition laid down pertaining to this tender.</strong>
        </label>
    </div>
    
    <!-- Next button -->
    <div class="next-button-container" id="nextButtonDiv" style="display: none;">
        <button type="button" class="next action-button" onclick="goToCategories()">Next</button>
    </div>
</div>

<style>
.terms-container {
    margin-top: 20px;
    font-family: Arial, sans-serif;
}

.terms-heading {
    margin: 15px 0 20px 0 !important;
    padding-bottom: 10px !important;
    border-bottom: 1px solid #dee2e6 !important;
    color: #007bff !important;
}

.terms-list {
    list-style-type: decimal;
    padding-left: 25px;
    line-height: 1.6;
    margin-bottom: 25px;
    font-size: 14px;
}

.terms-list li {
    margin-bottom: 12px;
}

.terms-list .sublist {
    list-style-type: lower-alpha;
    margin-top: 8px;
    margin-bottom: 8px;
    padding-left: 25px;
}

.terms-checkbox {
    margin: 25px 0 20px 0;
    padding: 12px 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

.terms-checkbox input[type="checkbox"] {
    margin-right: 12px;
    transform: scale(1.1);
}

.terms-checkbox label {
    font-size: 15px;
    color: #333;
    cursor: pointer;
}

.next-button-container {
    text-align: center;
    margin: 20px 0 10px 0;
}

.next-button-container .next {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 40px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

.next-button-container .next:hover {
    background-color: #0056b3;
}

/* Bootstrap classes if not already defined */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.col-12 {
    flex: 0 0 100%;
    max-width: 100%;
    padding-right: 15px;
    padding-left: 15px;
}

.text-end {
    text-align: right !important;
}

.steps {
    font-size: 18px;
    font-weight: 400;
    color: #6c757d;
}

.fs-title {
    font-size: 24px;
    font-weight: 500;
    line-height: 1.2;
    color: #333;
}
</style>

<script>
function toggleNextButton() {
    var checkbox = document.getElementById('agreeTerms');
    var nextButton = document.getElementById('nextButtonDiv');
    
    if (checkbox.checked) {
        nextButton.style.display = 'block';
    } else {
        nextButton.style.display = 'none';
    }
}

function goToCategories() {
    // Find the current fieldset (the approve/terms page)
    var current_fs = $("fieldset").first();
    
    // Find the next fieldset (the categories page)
    var next_fs = current_fs.next();
    
    // Add Class Active to the second progress bar li (index 1)
    $("#progressbar li").eq(1).addClass("active");
    
    // Show the next fieldset with animation
    next_fs.show();
    
    // Hide the current fieldset with animation
    current_fs.animate({
        opacity: 0
    }, {
        step: function(now) {
            // For making fieldset appear animation
            var opacity = 1 - now;
            current_fs.css({
                'display': 'none',
                'position': 'relative'
            });
            next_fs.css({
                'opacity': opacity
            });
        },
        duration: 500
    });
    
    // Update progress bar width - Step 2 of 3 = 66.66%
    $(".progress-bar").css("width", "66.66%");
}
</script>