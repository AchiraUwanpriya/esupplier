<?php
session_start();
date_default_timezone_set('Asia/Colombo');

include_once 'helper.php';

if (!isset($_SESSION['sup_code'])) {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['sup_status']) || $_SESSION['sup_status'] === "A") {
    header('Location: profile.php');
    exit();
}

// Ensure category is in session
if (!isset($_SESSION['sup_category']) && isset($_SESSION['sup_code'])) {
    include 'config.php';
    $supplier_code = $_SESSION['sup_code'];
    
    $query = "SELECT msd_supply_category FROM mms_supplier_pending_details WHERE msd_supplier_code = '$supplier_code'";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['sup_category'] = $row['msd_supply_category'];
    } else {
        $query = "SELECT msd_supply_category FROM mms_suppliers_details WHERE msd_supplier_code = '$supplier_code'";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['sup_category'] = $row['msd_supply_category'];
        }
    }
}

$user_category = $_SESSION['sup_category'] ?? '';
// ========== Fetch the category forms for this supplier ==========
include 'config.php';

function normalizeCategoryImagePath($path) {
    $path = trim((string)$path);
    if ($path === '') {
        return './static/img/9.png';
    }

    $path = str_replace('\\', '/', $path);
    if (!preg_match('/^(https?:\/\/|\.\/|\/)/i', $path)) {
        $path = './' . ltrim($path, '/');
    }

    return $path;
}

$categories = [];
if ($user_category) {
    $catQuery = "SELECT cat_code, display_name, image_path, sort_order 
                 FROM mms_category_forms 
                 WHERE supplier_category = ? 
                 ORDER BY sort_order";
    $stmt = mysqli_prepare($con, $catQuery);
    mysqli_stmt_bind_param($stmt, 's', $user_category);
    mysqli_stmt_execute($stmt);
    $catResult = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($catResult)) {
        $row['image_url'] = normalizeCategoryImagePath($row['image_path'] ?? '');
        
        // Fetch the unit type for this category (first item's unit)
        $unitQuery = "SELECT DISTINCT MMC_UNIT FROM mms_material_catalogue WHERE MMC_CAT_CODE = ? AND MMC_STATUS = 'A' LIMIT 1";
        $unitStmt = mysqli_prepare($con, $unitQuery);
        mysqli_stmt_bind_param($unitStmt, 's', $row['cat_code']);
        mysqli_stmt_execute($unitStmt);
        mysqli_stmt_bind_result($unitStmt, $unit);
        $row['unit'] = mysqli_stmt_fetch($unitStmt) ? $unit : '';
        mysqli_stmt_close($unitStmt);
        
        $categories[] = $row;
    }
    mysqli_stmt_close($stmt);
}
// ===============================================================

// POST handling (updated to work with dynamic categories)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert']) && $_POST['insert'] === 'category_save') {
    $success = true;
    $cat_code = $_POST['cat_code'] ?? '';
    // Optional: verify that this cat_code is allowed for the user's category
    // (you can query mms_category_forms again)

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

    // fetch the active tender for this category
    if ($user_category === 'RI') {
        $tnStmt = mysqli_prepare($con, "SELECT mtd_tender_no, mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1");
    } else {
        $tnStmt = mysqli_prepare($con, "SELECT mtd_tender_no, mtd_year FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = ? LIMIT 1");
        mysqli_stmt_bind_param($tnStmt, 's', $user_category);
    }
    mysqli_stmt_execute($tnStmt);
    mysqli_stmt_bind_result($tnStmt, $tenderNo, $tenderYear);
    $fetched = mysqli_stmt_fetch($tnStmt);
    mysqli_stmt_close($tnStmt);

    if (!$fetched) {
        $response = ['status' => 'error', 'message' => 'No active tender for your category'];
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode($response);
        } else {
            echo "<script>alert('No active tender found');</script>";
        }
        exit;
    }

    $suppliercode = $_SESSION['sup_code'];
    $sup_code = $suppliercode;
    $date_now = date('Y-m-d g:i A');

    // prepared statements
    $selectStmt = mysqli_prepare($con, "SELECT mtt_price, mtt_remark FROM mms_tenderprice_transactions WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A' LIMIT 1");
    $deleteStmt = mysqli_prepare($con, "DELETE FROM mms_tenderprice_transactions WHERE mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A' AND mtt_tender_no=?");
    $updateStmt = mysqli_prepare($con, "UPDATE mms_tenderprice_transactions SET mtt_remark=?, mtt_price=?, updated_by=?, updated_date=? WHERE mtt_year=? AND mtt_tender_no=? AND mtt_supplier_code=? AND mtt_material_code=? AND mtt_status='A'");
    $insertStmt = mysqli_prepare($con, "INSERT INTO mms_tenderprice_transactions (mtt_year,mtt_tender_no,mtt_supplier_code,mtt_material_code,mtt_remark,mtt_price,mtt_status,created_by,created_date) VALUES (?,?,?,?,?,?,?,?,?)");

    $rowCount = isset($_POST['MMC_DESCRIPTION']) ? count($_POST['MMC_DESCRIPTION']) : 0;

    for ($x = 0; $x < $rowCount; $x++) {
        $MMC_DESCRIPTION = $_POST['MMC_DESCRIPTION'][$x] ?? '';
        $MMC_UNIT = $_POST['MMC_UNIT'][$x] ?? '';
        $MMC_REMARK = (isset($_POST['MMC_REMARK'][$x]) && $_POST['MMC_REMARK'][$x] !== '') ? $_POST['MMC_REMARK'][$x] : null;
        $MMC_PRICE = (isset($_POST['MMC_PRICE'][$x]) && $_POST['MMC_PRICE'][$x] !== '') ? $_POST['MMC_PRICE'][$x] : null;
        $MMC_MATERIAL_CODE = $_POST['MMC_MATERIAL_CODE'][$x] ?? '';
        $MMC_CAT_CODE = $_POST['MMC_CAT_CODE'][$x] ?? '';

        if ($MMC_MATERIAL_CODE === '') continue;

        if ($MMC_PRICE === null) {
            // delete existing
            mysqli_stmt_bind_param($deleteStmt, 'sss', $suppliercode, $MMC_MATERIAL_CODE, $tenderNo);
            if (!mysqli_stmt_execute($deleteStmt)) { $success = false; }
            continue;
        }

        // check existing
        mysqli_stmt_bind_param($selectStmt, 'ssss', $tenderYear, $tenderNo, $suppliercode, $MMC_MATERIAL_CODE);
        if (!mysqli_stmt_execute($selectStmt)) { $success = false; continue; }
        mysqli_stmt_store_result($selectStmt);

        if (mysqli_stmt_num_rows($selectStmt) > 0) {
            mysqli_stmt_bind_result($selectStmt, $existingPrice, $existingRemark);
            mysqli_stmt_fetch($selectStmt);
        } else {
            $existingPrice = null;
            $existingRemark = null;
        }

        // skip if unchanged
        if ($existingPrice !== null && (string)$existingPrice === (string)$MMC_PRICE && (string)$existingRemark === (string)$MMC_REMARK) {
            continue;
        }

        if ($existingPrice !== null) {
            mysqli_stmt_bind_param($updateStmt, 'sdssssss', $MMC_REMARK, $MMC_PRICE, $sup_code, $date_now, $tenderYear, $tenderNo, $suppliercode, $MMC_MATERIAL_CODE);
            if (!mysqli_stmt_execute($updateStmt)) { $success = false; }
        } else {
            $status = 'A';
            mysqli_stmt_bind_param($insertStmt, 'sssssdsss', $tenderYear, $tenderNo, $suppliercode, $MMC_MATERIAL_CODE, $MMC_REMARK, $MMC_PRICE, $status, $sup_code, $date_now);
            if (!mysqli_stmt_execute($insertStmt)) { $success = false; }
        }
    }

    // close statements
    mysqli_stmt_close($selectStmt);
    mysqli_stmt_close($deleteStmt);
    mysqli_stmt_close($updateStmt);
    mysqli_stmt_close($insertStmt);

    mysqli_close($con);

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => $success ? 'success' : 'error']);
    } else {
        if ($success) echo "<script>alert('Data saved successfully!');</script>";
        else echo "<script>alert('Error occurred while saving!');</script>";
    }
    exit;
}

include './components/timecounter.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="./static/img/9.png" />
    <title>eSupplier-CDPLC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" />
    <script src="./static/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./static/js/app.js"></script>
    <link href="./static/css/app.css" rel="stylesheet">
    <link href="./static/css/main.css" rel="stylesheet">
    <style>
        table th { position: sticky; top: 0; background-color: green; }
        .fade-scale { transition: all .25s linear; }
        .popup-message { display: none; bottom: 20px; right: 20px; background-color: #fff; padding: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); animation: popupAnimation 0.5s ease-out; }
        @keyframes popupAnimation { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
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
                <input type="button" class="btn btn-success btn-lg" value="Submit a Tender" id="btndonemodal" onclick="document.getElementById('btnDoneFunc').click()" />
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
                                <center><img class="center pt-2" src="./static/img/cdl_logo.png" style="width: 15%" alt=""></center>
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
                                    <?php require('pages/approve.php'); ?>

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
                                        <input type="button" name="next" class="next action-button" value="Next" />
                                    </fieldset>

                                    <!-- Page 3 - Completed -->
                                    <?php require('pages/completed.php'); ?>
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
                                    // Query items for this cat_code
                                    $suppliercode = $_SESSION['sup_code'];
                                    if ($user_category === 'RI') {
                                        $tenderSubquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type IS NULL LIMIT 1)";
                                    } else {
                                        $tenderSubquery = "(SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A' AND mtd_type = '$user_category' LIMIT 1)";
                                    }
                                    $itemQuery = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE, MMC_CAT_CODE,
                                                         mtt_price AS MMC_PRICE, mtt_remark AS MMC_REMARK
                                                  FROM mms_material_catalogue
                                                  LEFT JOIN mms_tenderprice_transactions 
                                                      ON mtt_material_code = MMC_MATERIAL_CODE 
                                                      AND mtt_supplier_code = '$suppliercode'
                                                      AND mtt_tender_no = $tenderSubquery
                                                  WHERE MMC_CAT_CODE = '$catCode' AND MMC_STATUS = 'A'
                                                  ORDER BY MMC_DESCRIPTION ASC";
                                    $itemResult = mysqli_query($con, $itemQuery);
                                    $idx = 0;
                                    while ($row = mysqli_fetch_assoc($itemResult)): ?>
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
                                    <?php $idx++; endwhile; ?>
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
    // Handle all category form submissions via AJAX
    $('.category-form').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var formData = form.serialize();
        var catCode = form.data('catcode');

        $.ajax({
            type: 'POST',
            url: 'dashboard.php?stage=2',
            data: formData,
            dataType: 'json',
            success: function(response) {
                var msgSpan = $('#lb-' + catCode);
                if (response.status === 'success') {
                    msgSpan.text('Data Saved Successfully').fadeIn().delay(1500).fadeOut();
                } else {
                    msgSpan.text('Save Failed').fadeIn().delay(1500).fadeOut();
                }
                // Optionally refresh the preview tabs
                loadPreviewData();
            },
            error: function() {
                $('#lb-' + catCode).text('Error').fadeIn().delay(1500).fadeOut();
            }
        });
    });

    // Function to load preview data (all items and category-specific items)
    function loadPreviewData() {
        // Load all items (you may need an additional script; here we assume allitemsinventory.php still works)
        $.get('allitemsinventory.php', function(response) {
            if (response) {
                let items = JSON.parse(response);
                let rows = '';
                items.forEach(item => {
                    rows += `<tr><td>${item.CategoryName}</td><td>${item.MMC_DESCRIPTION}</td><td>${item.mtt_price}</td></tr>`;
                });
                $('#allitems').html(rows);
                // Enable/disable submit button based on emptiness
                if (items.length === 0) {
                    $('#btndonemodal').prop('disabled', true);
                    $('#submitTenderMessage').show();
                } else {
                    $('#btndonemodal').prop('disabled', false);
                    $('#submitTenderMessage').hide();
                }
            }
        });

        // Load each category tab
        <?php foreach ($categories as $cat): 
            $tabId = 'pills-' . strtolower($cat['cat_code']); ?>
        $.get('getCategoryItems.php', { cat_code: '<?= $cat['cat_code'] ?>' }, function(response) {
            let items = JSON.parse(response);
            let rows = '';
            items.forEach(item => {
                rows += `<tr><td>${item.MMC_DESCRIPTION}</td><td>${item.mtt_price}</td></tr>`;
            });
            $('#<?= $tabId ?>-items').html(rows);
        });
        <?php endforeach; ?>
    }

    // Load preview data when modal is opened
    $('#previewitemlist').on('show.bs.modal', function() {
        loadPreviewData();
    });

    // Initial load for any other purpose (optional)
    // loadPreviewData();
});
</script>

<!-- Timer script and others -->
<script src="js/sessionUnset.js"></script>
<script src="js/translate.js"></script>
</body>
</html>