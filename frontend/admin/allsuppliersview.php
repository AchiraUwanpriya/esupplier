<?php
session_start();

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
    header('Location: ../admin.php');
    exit();
}

$entry = $_SESSION['entry'];

require_once __DIR__ . '/../../backend/supplier/supplier_queries.php';

$supplierQueries = new SupplierQueries();
$pendingSuppliers = $supplierQueries->getPendingSuppliers();
$viewButtonDisabled = ($entry === 'N');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../static/img/2.svg" />
    <title>eSupplier-CDL</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../static/css/app.css" rel="stylesheet">
    <link href="../static/css/main.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        .btn {
            background-color: DodgerBlue;
            border: none;
            color: white;
            cursor: pointer;
        }

        /* Darker background on mouse-over */
        .btn:hover {
            background-color: RoyalBlue;
        }
    </style>

    <script src="../static/js/jquery-3.3.1.min.js"></script>
    <script src="../static/js/jquery.validate.min.js"></script>
    <script src="../static/js/jquery.validate.unobtrusive.min.js"></script>
    <script src="../static/js/app.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
        function myFunctionVeg() {
            alert("Data Saved Successfully!!!");
        }
    </script>

    <!-- checkbox -->
    <script>
        function myFunction1() {
            // Get the checkbox
            var checkBox = document.getElementById("myCheck");
            // Get the output text
            var text = document.getElementById("text");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true) {
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <?php include 'components/adminsidenav.php'; ?>

        <div class="main">
            <?php include 'components/adminnavbar.php'; ?>

            <!-- dashboard content -->
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3"><strong>Pending Suppliers</strong></h1>

                    <div style="height: 100%; overflow-y: scroll;">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered rounded">
                                            <thead>
                                                <tr style="background-color: mediumseagreen; color: white;">
                                                    <th scope="col">Supplier Code</th>
                                                    <th scope="col">Supplier Name</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Mobile</th>
                                                    <th scope="col">Supplier Category</th>
                                                    <th scope="col">Address</th>
                                                    <th scope="col">Action</th>
                                                    <th scope="col">Action</th>
                                                    <!-- <th scope="col">Status</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pendingSuppliers as $index => $row): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['msd_supplier_code']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['msd_supplier_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['msd_email_address']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['msd_mobileno']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['msd_supply_category_label']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['msd_address']); ?></td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input
                                                                    <?php if ($viewButtonDisabled) echo 'disabled'; ?>
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    id="approve_<?php echo $index; ?>"
                                                                    onclick="approveSupplier(this)"
                                                                    data-supplier-code="<?php echo htmlspecialchars($row['msd_supplier_code'], ENT_QUOTES); ?>"
                                                                    data-supplier-name="<?php echo htmlspecialchars($row['msd_supplier_name'], ENT_QUOTES); ?>"
                                                                    data-email="<?php echo htmlspecialchars($row['msd_email_address'], ENT_QUOTES); ?>"
                                                                    data-mobile="<?php echo htmlspecialchars($row['msd_mobileno'], ENT_QUOTES); ?>"
                                                                    data-category="<?php echo htmlspecialchars($row['msd_supply_category'], ENT_QUOTES); ?>"
                                                                    data-category-description="<?php echo htmlspecialchars($row['msd_supply_category_des'] ?? '', ENT_QUOTES); ?>"
                                                                    data-address="<?php echo htmlspecialchars($row['msd_address'], ENT_QUOTES); ?>"
                                                                >
                                                                <label class="form-check-label fw-bold text-success" for="approve_<?php echo $index; ?>">Approve</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button
                                                                type="button"
                                                                class="btn btn-danger"
                                                                onclick="deleteSupplier(this)"
                                                                data-supplier-code="<?php echo htmlspecialchars($row['msd_supplier_code'], ENT_QUOTES); ?>"
                                                            >
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- footer -->
            <?php include 'components/adminfooter.php'; ?>
        </div>
    </div>

    <script>
        function submitSupplierAction(payload, onSuccess, onFailure) {
            $.ajax({
                url: '../../backend/supplier/supplier_approval_action.php',
                type: 'POST',
                dataType: 'json',
                data: payload,
                timeout: 15000,
                success: function(response) {
                    if (response && response.success) {
                        onSuccess(response);
                    } else {
                        onFailure(response && response.message ? response.message : 'Action failed');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Unable to process request';
                    onFailure(message);
                }
            });
        }

        function approveSupplier(element) {
            if (!element.checked) {
                return;
            }

            if (!confirm('Are you sure you want to approve this supplier?')) {
                element.checked = false;
                return;
            }

            const payload = {
                action: 'approve',
                supplier_code: element.dataset.supplierCode,
                supplier_name: element.dataset.supplierName,
                email: element.dataset.email,
                mobile: element.dataset.mobile,
                category: element.dataset.category,
                category_description: element.dataset.categoryDescription,
                address: element.dataset.address
            };

            submitSupplierAction(
                payload,
                function() {
                    alert('Supplier has been approved successfully!');
                    window.location.reload();
                },
                function(errorMessage) {
                    element.checked = false;
                    alert(errorMessage);
                }
            );
        }

        function deleteSupplier(element) {
            if (!confirm('Do you want to delete the supplier?')) {
                return;
            }

            const payload = {
                action: 'delete',
                supplier_code: element.dataset.supplierCode
            };

            submitSupplierAction(
                payload,
                function() {
                    alert('Supplier deleted successfully!');
                    window.location.reload();
                },
                function(errorMessage) {
                    alert(errorMessage);
                }
            );
        }
    </script>
</body>
</html>
