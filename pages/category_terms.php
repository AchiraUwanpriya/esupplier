<?php
// File: pages/category_terms.php

// Include helper functions
require_once dirname(__DIR__) . '/backend/common/config.php';
require_once dirname(__DIR__) . '/backend/common/helper.php';

// Get supplier code from session
$supplier_code = isset($_SESSION['sup_code']) ? $_SESSION['sup_code'] : '';

// Get the supplier's actual category name
$supplier_category = Helper::getSupplierCategoryName($con, $supplier_code);

// Get category ID from supplier code
$cat_id = Helper::getCategoryIdFromSupplierCode($con, $supplier_code);

// Get category name from ID (for display)
$category_name = Helper::getCategoryNameFromId($con, $cat_id);

// Debug output (visible in page source)
echo "<!-- DEBUG INFO -->";
echo "<!-- Supplier Code: " . htmlspecialchars($supplier_code) . " -->";
echo "<!-- Supplier Category from DB: " . htmlspecialchars($supplier_category) . " -->";
echo "<!-- Mapped Category ID: " . $cat_id . " -->";
echo "<!-- Display Category Name: " . htmlspecialchars($category_name) . " -->";

// Query to get terms using the correct category_id
$terms_query = "SELECT ct_id, ct_term, ct_order 
                FROM category_terms 
                WHERE ct_category_id = $cat_id
                AND ct_status = 'A' 
                ORDER BY ct_order ASC";

$terms_result = mysqli_query($con, $terms_query);

if (!$terms_result) {
    echo "<!-- Query Error: " . mysqli_error($con) . " -->";
} else {
    echo "<!-- Terms Found: " . mysqli_num_rows($terms_result) . " -->";
}
?>

<!-- TERMS & CONDITIONS CARD -->
<div class="terms-container">
    <h2 class="fs-title terms-heading">TERMS & CONDITIONS - <?php echo htmlspecialchars($category_name); ?></h2>
    
    <ol class="terms-list">
        <?php 
        if ($terms_result && mysqli_num_rows($terms_result) > 0) {
            while($term = mysqli_fetch_assoc($terms_result)) {
                echo '<li>' . htmlspecialchars($term['ct_term']) . '</li>';
            }
        } else {
            echo '<li>No specific terms and conditions found for ' . htmlspecialchars($category_name) . '.</li>';
        }
        ?>
    </ol>
    
    <div class="terms-checkbox">
        <input type="checkbox" id="agreeTerms" onchange="toggleNextButton()">
        <label for="agreeTerms">
            <strong>We agreed to abide by the terms & condition laid down pertaining to this tender.</strong>
        </label>
    </div>
    
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
</style>

<script>
function toggleNextButton() {
    var checkbox = document.getElementById('agreeTerms');
    var nextButton = document.getElementById('nextButtonDiv');
    nextButton.style.display = checkbox.checked ? 'block' : 'none';
}

function goToCategories() {
    var current_fs = $("fieldset").first();
    var next_fs = current_fs.next();
    
    $("#progressbar li").eq(1).addClass("active");
    next_fs.show();
    
    current_fs.animate({
        opacity: 0
    }, {
        step: function(now) {
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
    
    $(".progress-bar").css("width", "66.66%");
}
</script>