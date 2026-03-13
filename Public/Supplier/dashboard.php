<?php


session_start();
date_default_timezone_set('Asia/Colombo');

// Root of the project (two levels up from Public/Supplier/)
$__root = __DIR__ . '/../../';

// Base URL prefix for HTML links/assets resolved in components
$sbase = '../../';

require_once $__root . 'backend/common/helper.php';

if (!isset($_SESSION['sup_code'])) {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['sup_status']) || $_SESSION['sup_status'] === "A") {
    header('Location: profile.php');
    exit();
}

require_once $__root . 'backend/supplier/dashboard_queries.php';
$dashboardQueries = new DashboardQueries();

// Ensure category is in session
if (!isset($_SESSION['sup_category']) && isset($_SESSION['sup_code'])) {
    $supplier_code = $_SESSION['sup_code'];
    $category = $dashboardQueries->getSupplierCategory($supplier_code);
    if ($category) {
        $_SESSION['sup_category'] = $category;
    }
}

$user_category = $_SESSION['sup_category'] ?? '';

function normalizeCategoryImagePath($path) {
    $path = trim((string)$path);
    if ($path === '') {
        return '../static/img/9.png';
    }
    $path = str_replace('\\', '/', $path);
    if (!preg_match('/^(https?:\/\/|\.\/|\/)/i', $path)) {
        $path = './' . ltrim($path, '/');
    }
    return $path;
}

$categories = [];
if ($user_category) {
    $fetchedCategories = $dashboardQueries->getCategoriesBySupplierCategory($user_category);
    foreach ($fetchedCategories as $row) {
        $row['image_url'] = normalizeCategoryImagePath($row['image_path'] ?? '');
        $row['unit'] = $dashboardQueries->getUnitForCategory($row['cat_code']);
        $categories[] = $row;
    }
}

// POST handling
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert']) && $_POST['insert'] === 'category_save') {
    $success = true;
    $cat_code = $_POST['cat_code'] ?? '';

    $user_category = $_SESSION['sup_category'] ?? '';
    if ($user_category === '') {
        $response = ['status' => 'error', 'message' => 'No user category'];
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode($response);
        } else {
            echo "<script>alert('User category not set');</script>";
        }
        exit;
    }

    $activeTender = $dashboardQueries->getActiveTender($user_category);

    if (!$activeTender) {
        $response = ['status' => 'error', 'message' => 'No active tender for your category'];
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode($response);
        } else {
            echo "<script>alert('No active tender found');</script>";
        }
        exit;
    }

    $tenderNo = $activeTender['mtd_tender_no'];
    $tenderYear = $activeTender['mtd_year'];
    
    // Initialize display variables for the tender info section
    $tNumber = $tenderNo;
    $bidclose_date = $activeTender['mtd_bidclose_date'] ?? 'N/A';
    $stardate = $activeTender['mtd_start_date'] ?? 'N/A';
    $enddate = $activeTender['mtd_end_date'] ?? 'N/A';
    
    $suppliercode = $_SESSION['sup_code'];

    $itemsData = [];
    $rowCount = isset($_POST['MMC_DESCRIPTION']) ? count($_POST['MMC_DESCRIPTION']) : 0;

    for ($x = 0; $x < $rowCount; $x++) {
        $itemsData[] = [
            'MMC_MATERIAL_CODE' => $_POST['MMC_MATERIAL_CODE'][$x] ?? '',
            'MMC_PRICE' => (isset($_POST['MMC_PRICE'][$x]) && $_POST['MMC_PRICE'][$x] !== '') ? $_POST['MMC_PRICE'][$x] : null,
            'MMC_REMARK' => (isset($_POST['MMC_REMARK'][$x]) && $_POST['MMC_REMARK'][$x] !== '') ? $_POST['MMC_REMARK'][$x] : null
        ];
    }

    $success = $dashboardQueries->saveTenderPrices($tenderYear, $tenderNo, $suppliercode, $itemsData);

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => $success ? 'success' : 'error']);
    } else {
        if ($success) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data saved successfully!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) { location.reload(); }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error occurred while saving!',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    }
    exit;
}

// Ensure variables are defined if not in POST
if (!isset($tNumber) && isset($user_category)) {
    $activeTender = $dashboardQueries->getActiveTender($user_category);
    if ($activeTender) {
        $tNumber = $activeTender['mtd_tender_no'] ?? 'N/A';
        $bidclose_date = $activeTender['mtd_bidclose_date'] ?? 'N/A';
        $stardate = $activeTender['mtd_start_date'] ?? 'N/A';
        $enddate = $activeTender['mtd_end_date'] ?? 'N/A';
    } else {
        $tNumber = $bidclose_date = $stardate = $enddate = 'N/A';
    }
}

include './components/timecounter.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../static/img/9.png" />
    <title>eSupplier-CDPLC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" />
    <script src="../static/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../static/js/app.js"></script>
    <link href="../static/css/app.css" rel="stylesheet">
    <link href="../static/css/main.css" rel="stylesheet">
    <style>
        table th { position: sticky; top: -20px; background-color: green; z-index: 5; }
        .fade-scale { transition: all .25s linear; }
        .popup-message { display: none; bottom: 20px; right: 20px; background-color: #fff; padding: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); animation: popupAnimation 0.5s ease-out; }
        @keyframes popupAnimation { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        .modal-header { position: sticky; top: 0; z-index: 20; background-color: white; }
    </style>
</head>
<body>

<!-- Preview Modal (dynamic tabs) -->
<div class="modal fade-scale" id="previewitemlist" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fw-bold">Preview Saved Items</h3>
                <h4 class="popup-message" id="submitTenderMessage" style="display: none; color: red;">Please add prices to submit the tender</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab">All List</button>
                    </li>
                    <?php foreach ($categories as $cat):
                        $tabId = 'pills-' . strtolower($cat['cat_code']); ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="<?= $tabId ?>-tab" data-bs-toggle="pill" data-bs-target="#<?= $tabId ?>" type="button" role="tab"><?= htmlspecialchars($cat['display_name']) ?></button>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-all" role="tabpanel">
                        <table class="table table-hover table-bordered border-primary">
                            <thead><tr><th class="bg-info">Category</th><th class="bg-info">Description</th><th class="bg-info">Price (Rs.)</th></tr></thead>
                            <tbody id="allitems"></tbody>
                        </table>
                    </div>
                    <?php foreach ($categories as $cat):
                        $tabId = 'pills-' . strtolower($cat['cat_code']); ?>
                    <div class="tab-pane fade" id="<?= $tabId ?>" role="tabpanel">
                        <table class="table table-hover table-bordered border-primary">
                            <thead><tr><th class="bg-info">Description</th><th class="bg-info">Price (Rs.)</th></tr></thead>
                            <tbody id="<?= $tabId ?>-items"></tbody>
                        </table>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal">Close</button>
                <input type="button" class="btn btn-success btn-lg" value="Submit a Tender" id="btndonemodal" />
            </div>
        </div>
    </div>
</div>

<div class="wrapper">
    <?php include './components/sidenav.php'; ?>
    <div class="main">
        <?php include './components/navbar.php'; ?>
        <main class="content">
            <div class="container-fluid p-0">
                <div class="container-fluid">
                    <!-- time counter -->
                    <div class="row justify-content-center">
                        <h1 id="TimeCounter" class="text-center" style="color:red; font-weight:bold"></h1>
                        <h2 class="text-center" style="color:red; font-weight:bold">The Bidding will close on <?= $bidclose_date ?></h2>
                        <h2 class="text-success font-weight-bold text-center" id="tenderopentime">
                            Tender Open from <?= $stardate; ?> to <?= $enddate; ?> | Tender No: <?= $tNumber; ?>
                        </h2>
                    </div>
                    <!-- time counter end -->

                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-10 col-md-10 col-lg-6 col-xl-12 p-0 mt-3 mb-2">
                            <div class="card px-0 pb-0">
                                <center><img class="center pt-2" src="../static/img/cdl_logo.png" style="width: 15%" alt=""></center>
                                <h3 class="text-center" style="color: blue;">
                                    <strong>
                                        <?php
                                        echo $user_category === "PI" ? "Tender For the Supply of PVC Items" :
                                            ($user_category === "MI" ? "Tender For the Supply of Medicine Items" :
                                            ($user_category === "CB" ? "Tender For the Supply of Cables" :
                                            "Tender For the Supply of Foods - Cash Price"));
                                        ?>
                                    </strong>
                                </h3>

                                <form id="msform" method="post">
                                    <ul id="progressbar">
                                        <li class="active" id="account"><strong>Approve</strong></li>
                                        <li id="personal"><strong>Categories</strong></li>
                                        <li id="confirm"><strong>Completed</strong></li>
                                    </ul>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div><br>

                                    <!-- Page 1 - Approve (Terms) -->
                                    <?php require(__DIR__ . '/pages/approve.php'); ?>

                                    <!-- Page 2 - Categories (dynamic buttons) -->
                                    <fieldset>
                                        <div class="form-card">
                                            <h2 class="fs-title">Select Category</h2>
                                            <div class="row g-3">
                                                <?php foreach ($categories as $cat): ?>
                                                    <div class="col-6 col-md-4 col-lg-3">
                                                        <button type="button" class="btn btn-light w-100 border shadow-sm category-btn h-100"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-<?= htmlspecialchars($cat['cat_code']) ?>">
                                                            <img src="<?= htmlspecialchars($cat['image_url']) ?>" style="width: 64px; height: 64px; object-fit: contain;" alt="<?= htmlspecialchars($cat['display_name']) ?>">
                                                            <div class="mt-2 fw-semibold text-dark"><?= htmlspecialchars($cat['display_name']) ?></div>
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <input type="button" id="categoriesCheckBtn" class="btn btn-primary action-button" value="Check" data-bs-toggle="modal" data-bs-target="#previewitemlist" />
                                        <input type="button" id="categoriesNextBtn" name="next" class="next action-button" value="Next" style="display:none;" />
                                        <button type="button" id="btnDoneFunc" hidden></button>
                                    </fieldset>

                                    <!-- Page 3 - Completed -->
                                    <?php require(__DIR__ . '/pages/completed.php'); ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamically generated modals for each category -->
            <?php foreach ($categories as $cat):
                $catCode = $cat['cat_code'];
                $modalId = 'modal-' . $catCode;
                $displayName = $cat['display_name'];
                $imagePath = $cat['image_url'];
            ?>
            <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <form method="POST" action="dashboard.php?stage=2" class="category-form" data-catcode="<?= $catCode ?>">
                            <div class="modal-header">
                                <h2 class="modal-title text-info"><?= $displayName ?></h2>
                                <img src="<?= $imagePath ?>" style="width: 80px;" alt="">
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body" style="max-height: calc(100vh - 220px); overflow-y: auto;">
                                <center><h3 class="text-success" id="lb-<?= $catCode ?>"></h3></center>
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="fixed">
                                            <th class="bg-success">Item Name</th>
                                            <th class="bg-success">Description</th>
                                            <th class="bg-success">Unit <?php echo ($cat['unit'] !== '') ? '(' . htmlspecialchars($cat['unit']) . ')' : ''; ?></th>
                                            <th class="bg-success">Remarks</th>
                                            <th class="bg-success">Tender Price (Rs.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $suppliercode = $_SESSION['sup_code'];
                                    $categoryItems = $dashboardQueries->getCategoryItems($catCode, $suppliercode, $user_category);
                                    $idx = 0;
                                    foreach ($categoryItems as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['MMC_DESCRIPTION']) ?>
                                                <input type="hidden" name="MMC_DESCRIPTION[<?= $idx ?>]" value="<?= htmlspecialchars($row['MMC_DESCRIPTION']) ?>">
                                            </td>
                                            <td><?= htmlspecialchars($row['MMC_MATERIAL_SPEC']) ?>
                                                <input type="hidden" name="MMC_MATERIAL_SPEC[<?= $idx ?>]" value="<?= htmlspecialchars($row['MMC_MATERIAL_SPEC']) ?>">
                                            </td>
                                            <td><?= htmlspecialchars($row['MMC_UNIT']) ?>
                                                <input type="hidden" name="MMC_UNIT[<?= $idx ?>]" value="<?= htmlspecialchars($row['MMC_UNIT']) ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align: right;" type="text"
                                                       name="MMC_REMARK[<?= $idx ?>]" value="<?= htmlspecialchars($row['MMC_REMARK']) ?>" placeholder="Remark">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align: right;" type="number" step="0.01"
                                                       name="MMC_PRICE[<?= $idx ?>]" value="<?= htmlspecialchars($row['MMC_PRICE']) ?>" placeholder="Price">
                                            </td>
                                            <td style="display:none">
                                                <input type="hidden" name="MMC_MATERIAL_CODE[<?= $idx ?>]" value="<?= htmlspecialchars($row['MMC_MATERIAL_CODE']) ?>">
                                                <input type="hidden" name="MMC_CAT_CODE[<?= $idx ?>]" value="<?= htmlspecialchars($row['MMC_CAT_CODE']) ?>">
                                            </td>
                                        </tr>
                                    <?php $idx++; endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="insert" value="category_save">
                                <input type="hidden" name="cat_code" value="<?= $catCode ?>">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success item-savebtn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </main>
        <?php include './components/footer.php'; ?>
    </div>
</div>

<!-- JavaScript: unified form submission and preview loading -->
<script>
$(document).ready(function() {
    function hasAtLeastOnePrice(form) {
        let hasPrice = false;
        form.find('input[name^="MMC_PRICE"]').each(function() {
            const value = $(this).val();
            if (value !== null && String(value).trim() !== '') { hasPrice = true; return false; }
        });
        return hasPrice;
    }

    function hasInvalidPrice(form) {
        let invalid = false;
        form.find('input[name^="MMC_PRICE"]').each(function() {
            const raw = $(this).val();
            if (raw === null || String(raw).trim() === '') return;
            const num = Number(raw);
            if (!Number.isFinite(num) || num < 0) { invalid = true; return false; }
        });
        return invalid;
    }

    function parseJsonResponse(response) {
        if (typeof response === 'object') return response;
        try { return JSON.parse(response); } catch (e) { return null; }
    }

    function closeActiveModal() {
        const openModalEl = document.querySelector('.modal.show');
        if (openModalEl) {
            const modal = bootstrap.Modal.getInstance(openModalEl) || new bootstrap.Modal(openModalEl);
            modal.hide();
        }
        const previewModalEl = document.getElementById('previewitemlist');
        if (previewModalEl && previewModalEl.classList.contains('show')) {
            const previewModal = bootstrap.Modal.getInstance(previewModalEl) || new bootstrap.Modal(previewModalEl);
            previewModal.hide();
        }
        setTimeout(function() {
            document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) { backdrop.remove(); });
            $('.modal').removeClass('show').css('display', '');
            $('body').removeClass('modal-open').css('overflow', '');
        }, 250);
    }

    $('.category-form').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        if (!hasAtLeastOnePrice(form)) {
            Swal.fire({ icon: 'warning', title: 'No Prices Entered', text: 'Please enter at least one tender price before saving.', confirmButtonColor: '#f0ad4e' });
            return;
        }
        if (hasInvalidPrice(form)) {
            Swal.fire({ icon: 'error', title: 'Invalid Price', text: 'Prices must be valid numbers greater than or equal to 0.', confirmButtonColor: '#d33' });
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'dashboard.php?stage=2',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    closeActiveModal();
                    Swal.fire({ icon: 'success', title: 'Success!', text: 'Data saved successfully', confirmButtonColor: '#3085d6', timer: 2000, timerProgressBar: true, didClose: function() { loadPreviewData(); } });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to save data', confirmButtonColor: '#d33' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'An error occurred while saving', confirmButtonColor: '#d33' });
            }
        });
    });

    function loadPreviewData() {
        $.get('allitemsinventory.php', function(response) {
            if (response) {
                const items = parseJsonResponse(response);
                if (!Array.isArray(items)) { $('#allitems').html(''); $('#btndonemodal').prop('disabled', true); $('#submitTenderMessage').show(); return; }
                const pricedItems = items.filter(function(item) { return item && item.mtt_price !== null && String(item.mtt_price).trim() !== ''; });
                let rows = '';
                pricedItems.forEach(function(item) { rows += `<tr><td>${item.CategoryName}</td><td>${item.MMC_DESCRIPTION}</td><td>${item.mtt_price}</td></tr>`; });
                $('#allitems').html(rows);
                if (pricedItems.length === 0) { $('#btndonemodal').prop('disabled', true); $('#submitTenderMessage').show(); }
                else { $('#btndonemodal').prop('disabled', false); $('#submitTenderMessage').hide(); }
            } else { $('#allitems').html(''); $('#btndonemodal').prop('disabled', true); $('#submitTenderMessage').show(); }
        });

        <?php foreach ($categories as $cat):
            $tabId = 'pills-' . strtolower($cat['cat_code']); ?>
        $.get('getcategoryitems.php', { cat_code: '<?= $cat['cat_code'] ?>' }, function(response) {
            const items = parseJsonResponse(response);
            if (!Array.isArray(items)) { $('#<?= $tabId ?>-items').html(''); return; }
            const pricedItems = items.filter(function(item) { return item && item.mtt_price !== null && String(item.mtt_price).trim() !== ''; });
            let rows = '';
            pricedItems.forEach(function(item) { rows += `<tr><td>${item.MMC_DESCRIPTION}</td><td>${item.mtt_price}</td></tr>`; });
            $('#<?= $tabId ?>-items').html(rows);
        });
        <?php endforeach; ?>
    }

    $('#previewitemlist').on('show.bs.modal', function() { loadPreviewData(); });

    $('#categoriesCheckBtn').show();
    $('#categoriesNextBtn').hide();

    $('#btndonemodal').on('click', function() {
        if ($(this).prop('disabled')) return;
        $.ajax({
            type: 'POST',
            url: 'SuppplierDone.php',
            data: {},
            success: function(response) {
                const text = String(response || '');
                const blocked = text.indexOf('Please input and save item values before submitting the tender.') !== -1 || text.indexOf('Database error:') !== -1;
                if (blocked) {
                    Swal.fire({ icon: 'warning', title: 'Cannot Submit Yet', text: 'Please input and save item values before submitting the tender.', confirmButtonColor: '#f0ad4e' });
                    return;
                }
                $('#categoriesCheckBtn').show();
                $('#categoriesNextBtn').hide();
                closeActiveModal();
                Swal.fire({ icon: 'success', title: 'Tender Submitted', text: 'Tender submitted successfully. Moving to completed step...', confirmButtonColor: '#3085d6', allowOutsideClick: false, timer: 1500, timerProgressBar: true, showConfirmButton: false, didClose: function() { $('#categoriesNextBtn').trigger('click'); } });
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Submit Failed', text: 'An error occurred while submitting the tender.', confirmButtonColor: '#d33' });
            }
        });
    });

    // ========== Form Stepper ==========
    var current_fs, next_fs, previous_fs;
    var opacity;

    $('.next').click(function(e) {
        e.preventDefault();
        current_fs = $(this).closest('fieldset');
        next_fs = current_fs.next();
        if (next_fs.length === 0) return;
        var next_index = $('fieldset').index(next_fs);
        $('#progressbar li').eq(next_index).addClass('active');
        next_fs.show();
        current_fs.animate({ opacity: 0 }, { step: function(now) { opacity = 1 - now; current_fs.css({'display':'none','position':'relative'}); next_fs.css({'opacity': opacity}); }, duration: 500 });
        var steps = $('fieldset').length;
        var percent = ((next_index + 1) / steps) * 100;
        $('.progress-bar').css('width', percent.toFixed(0) + '%');
    });

    $('.previous').click(function(e) {
        e.preventDefault();
        current_fs = $(this).closest('fieldset');
        previous_fs = current_fs.prev();
        if (previous_fs.length === 0) return;
        var current_index = $('fieldset').index(current_fs);
        $('#progressbar li').eq(current_index).removeClass('active');
        previous_fs.show();
        current_fs.animate({ opacity: 0 }, { step: function(now) { opacity = 1 - now; current_fs.css({'display':'none','position':'relative'}); previous_fs.css({'opacity': opacity}); }, duration: 500 });
        var prev_index = $('fieldset').index(previous_fs);
        var steps = $('fieldset').length;
        var percent = ((prev_index + 1) / steps) * 100;
        $('.progress-bar').css('width', percent.toFixed(0) + '%');
    });
});
</script>

<!-- Timer script and others -->
<script src="../js/sessionUnset.js"></script>
<script src="../js/translate.js"></script>
</body>
</html>
