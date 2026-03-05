<?php
session_start();
include '../config.php';

// Admin authentication
if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: ../admin.php');
    exit();
}

$entry = $_SESSION['entry'];

// Include query functions
require_once __DIR__ . '/../backend/queries/material_catalogue_queries.php';

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
            $_POST['Status'],
            $sup_code,
            $date_now
        );
        
        if ($result['status']) {
            echo $result['message'];
            header('Refresh: 1; url=' . $_SERVER['REQUEST_URI']);
        } else {
            echo $result['message'];
        }
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
        if ($result['status']) {
            echo $result['message'];
            header('Refresh: 1; url=' . $_SERVER['REQUEST_URI']);
        } else {
            echo $result['message'];
        }
        exit;
    }
}

// ============ FIXED CATEGORY LIST (matching old UI) ============
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

// Icon mapping
function getIcon($code) {
    switch ($code) {
        case 'V': return '../static/img/vegetable.png';
        case 'S': return '../static/img/spice.png';
        case 'F': return '../static/img/fish.png';
        case 'D': return '../static/img/dried-fish.png';
        case 'Y': return '../static/img/dried-item.png';
        case 'C': return '../static/img/coconut.png';
        case 'O': return '../static/img/coconut-oil.png';
        case 'R': return '../static/img/rice.png';
        case 'H': return '../static/img/chicken-leg.png';
        case 'M': return '../static/img/gift-wrapping.png';
        case 'P': return '../static/img/Pvc.png';
        case 'I': return '../static/img/medicine.png';
        case 'E': return '../static/img/eggs.png';
        case 'B': return '../static/img/cables.png';
        default: return '../static/img/2.svg';
    }
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../static/img/2.svg" />
    <title>eSupplier-CDL</title>
    <!-- Bootstrap & other CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../static/css/app.css" rel="stylesheet">
    <link href="../static/css/main.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom card styling for a professional look -->
    <style>
        .category-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
       .category-card .card-img-top {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin: 20px auto 10px;
    display: block;
}
        .category-card .card-body {
            text-align: center;
            padding: 1rem 0.5rem 1.5rem;
        }
        .category-card .card-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .category-card a {
            text-decoration: none;
            color: inherit;
        }
        .category-card a:hover {
            color: #007bff;
        }
        /* Responsive grid improvements */
        .row-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>

    <script src="../static/js/jquery-3.3.1.min.js"></script>
    <script src="../static/js/jquery.validate.min.js"></script>
    <script src="../static/js/jquery.validate.unobtrusive.min.js"></script>
    <script src="../static/js/app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Modal Update Script -->
    <script>
        function myFunctionVeg() {
            alert("Data Inserted Successfully!!!");
        }

        function selectProduct(materialCode, desc, spec, unit, sts) {
            $("#MaterialCode_hidden").val(materialCode);
            $("#MaterialCode").val(materialCode);
            $('#Description').val(desc);
            $('#MaterialSpec').val(spec);
            $('#Unit').val(unit);
            $('#stsactive').prop('checked', sts === true || sts === '1' || sts === 'A');
            $('#stsinactive').prop('checked', !(sts === true || sts === '1' || sts === 'A'));
        }

        // Set category code in the global add modal before opening
        function setAddModalCatCode(catCode) {
            $('#addModalCatCode').val(catCode);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar (unchanged) -->
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="../adminview.php">
                    <center><img src="../static/img/8.png" class="mt-3" style=" width: 100%; padding-right: 30px;" alt=""></center>
                </a>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">Supplier Managment</li>
                    <li class="sidebar-item "><a class="sidebar-link" href="allsuppliersview.php"><i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Pending Suppliers</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="allactivesuppliersview.php"><i class="align-middle" data-feather="users"></i> <span class="align-middle">Registered Suppliers</span></a></li>
                    <li class="sidebar-header">Tender Managment</li>
                    <li class="sidebar-item"><a class="sidebar-link" href="tenderview.php"><i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Tenders</span></a></li>
                    <li class="sidebar-item "><a class="sidebar-link" href="monthlytenderview.php"><i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Monthly Tenders</span></a></li>
                    <?php if ($entry != 'N') : ?>
                    <li class="sidebar-header">Food Managment</li>
                    <li class="sidebar-item active"><a class="sidebar-link" href="addfood.php"><i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Add Food</span></a></li>
                    <?php endif; ?>
                </ul>
                <div class="sidebar-cta">
                    <div class="sidebar-cta-content">
                        <div class="d-grid">
                            <a href="../adminlogout.php" class="btn btn-primary" onclick="logoutfunction()">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <script>
            function logoutfunction() { alert("Please Confirm To Logout!!"); }
        </script>

        <div class="main">
            <!-- Top navbar -->
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle"><i class="hamburger align-self-center"></i></a>
                <a href="" style="color: blue; font-weight: bolder; text-decoration: none;">HELLO <?php echo $_SESSION['name'] ?>! WELCOME TO eSupplier-CDPLC ADMIN DASHBOARD!!!</a>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown"><i class="align-middle" data-feather="settings"></i></a>
                            <a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img src="../static/img/avatars/avatar1.jpg" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark"><?php echo $_SESSION['name'] ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-divider"> </div>
                                <a href="logout.php" class="dropdown-item" onclick="logoutfunction()">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3"><strong>eSupplier Add Food</strong></h1>

                    <!-- Professional category cards -->
                    <div class="row row-cards g-4 justify-content-center" style="padding: 20px 0;">
                        <?php foreach ($categories as $cat): 
                            $code = $cat['code'];
                            $name = $cat['name'];
                            $icon = getIcon($code);
                        ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 d-flex align-items-stretch">
                                <div class="card category-card w-100">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_<?php echo $code; ?>">
                                        <img class="card-img-top" src="<?php echo $icon; ?>" alt="<?php echo $name; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $name; ?> TENDER</h5>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- ========== CATEGORY MODALS (items fetched from DB) ========== -->
                    <?php foreach ($categories as $cat): 
                        $code = $cat['code'];
                        $catName = $cat['name'];
                        $icon = getIcon($code);
                        // Fetch materials for this category (if any)
                        $mat_query = "SELECT MMC_MATERIAL_CODE, MMC_DESCRIPTION, MMC_MATERIAL_SPEC, MMC_UNIT, MMC_STATUS
                                      FROM mms_material_catalogue
                                      WHERE MMC_CAT_CODE = '$code'
                                      ORDER BY MMC_DESCRIPTION";
                        $mat_result = mysqli_query($con, $mat_query);
                    ?>
                        <!-- Modal for category <?php echo $code; ?> -->
                        <div class="modal fade" id="modal_<?php echo $code; ?>" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalLabel_<?php echo $code; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title text-info" id="modalLabel_<?php echo $code; ?>"><?php echo $catName; ?> ITEMS</h2>
                                        <img src="<?php echo $icon; ?>" style="width: 80px;" alt="">
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th><u><h5 class="fw-bold">Material Code</h5></u></th>
                                                    <th><u><h5 class="fw-bold">Description</h5></u></th>
                                                    <th><u><h5 class="fw-bold">Spec</h5></u></th>
                                                    <th><u><h5 class="fw-bold">Unit1</h5></u></th>
                                                    <th><u><h5 class="fw-bold">Action</h5></u></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (mysqli_num_rows($mat_result) > 0): ?>
                                                    <?php while ($row = mysqli_fetch_assoc($mat_result)): ?>
                                                        <tr>
                                                            <td><h6><?php echo $row['MMC_MATERIAL_CODE']; ?></h6></td>
                                                            <td><h6><?php echo $row['MMC_DESCRIPTION']; ?></h6></td>
                                                            <td><h6><?php echo $row['MMC_MATERIAL_SPEC']; ?></h6></td>
                                                            <td><h6><?php echo $row['MMC_UNIT']; ?></h6></td>
                                                            <td>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
                                                                    <button type="button" class="btn btn-warning updatebtn" 
                                                                            onclick="selectProduct('<?php echo $row['MMC_MATERIAL_CODE']; ?>', 
                                                                                                    '<?php echo addslashes($row['MMC_DESCRIPTION']); ?>', 
                                                                                                    '<?php echo addslashes($row['MMC_MATERIAL_SPEC']); ?>', 
                                                                                                    '<?php echo $row['MMC_UNIT']; ?>', 
                                                                                                    '<?php echo $row['MMC_STATUS'] === 'A' ? '1' : '0'; ?>')">
                                                                        Update
                                                                    </button>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr><td colspan="5" class="text-center">No items found in this category.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#globalAddModal"
                                                onclick="setAddModalCatCode('<?php echo $code; ?>')">
                                            Add
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- ========== GLOBAL UPDATE MODAL ========== -->
                    <div class="modal fade" id="exampleModalScrollableupdate" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title text-info" id="exampleModalScrollableTitle">UPDATE DETAILS</h2>
                                </div>
                                <div class="modal-body">
                                    <form id="updateForm" method="POST">
                                        <table class="table table-hover">
                                            <div class="form-group row">
                                                <label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="MaterialCode" id="MaterialCode_hidden" hidden>
                                                    <input type="text" class="form-control" name="MaterialCode_display" id="MaterialCode" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="Description" class="col-sm-2 col-form-label">Description:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control-plaintext" name="Unit" id="Unit" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Status:</label>
                                                <div class="col-sm-10">
                                                    <input type='radio' name='Status' value='A' id="stsactive"> Active
                                                    <input type='radio' name='Status' value='I' id="stsinactive"> Inactive
                                                </div>
                                            </div>
                                        </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
                                    <button type="submit" name="updatebtn" id="updatebtn" class="btn btn-success" onclick="modalfunction()">Save changes</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ========== GLOBAL ADD MODAL ========== -->
                    <div class="modal fade" id="globalAddModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="globalAddModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title text-info" id="globalAddModalLabel">ADD NEW ITEM</h2>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addForm" method="POST">
                                        <input type="hidden" name="CatCode" id="addModalCatCode" value="">
                                        <table class="table table-hover">
                                            <div class="form-group row">
                                                <label for="addMaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="MaterialCode" id="addMaterialCode" placeholder="Material Code" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="addDescription" class="col-sm-2 col-form-label">Description:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="Description" id="addDescription" placeholder="Description" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="addMaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="MaterialSpec" id="addMaterialSpec" placeholder="Material Spec">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="addUnit" class="col-sm-2 col-form-label">Unit:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" readonly class="form-control-plaintext" name="Unit" id="addUnit" value="KGS">
                                                </div>
                                            </div>
                                        </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
                                    <button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">Save changes</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include '../components/footer.php'; ?>
        </div>
    </div>

    <!-- Modal functions & focus fix -->
    <script>
        function modalfunction() {
            // alert("Records Saved Successfully!!");
        }
        function modalclosefunction() {
            // alert("Are You Sure!!");
        }

        $(document).ready(function() {
            // Before modal hides, blur any focused element inside it
            $('.modal').on('hide.bs.modal', function () {
                if ($(this).find(':focus').length) {
                    $(this).find(':focus').blur();
                }
            });
            
            // After modal hides, double-check and move focus to body
            $('.modal').on('hidden.bs.modal', function () {
                if ($(this).find(':focus').length) {
                    $(this).find(':focus').blur();
                }
                // Move focus to a safe element (e.g., body)
                document.body.focus();
            });
        });
    </script>
</body>
</html>