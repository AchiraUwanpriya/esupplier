<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="shortcut icon" href="./static/img/9.png" />

    <title>eSupplier-CDPLC</title>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="./static/css/app.css" rel="stylesheet">
    <link href="./static/css/main.css" rel="stylesheet">
</head>

<body>
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="dashboard.php">
                <!-- <span class="align-middle">eSupplier-CDL</span> -->
                <center>
                    <img src="./static/img/8.png" class="mt-3" style=" width: 100%; padding-right: 30px;" alt="">
                </center>
            </a>
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Pages
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="dashboard.php">
                        <i class="align-middle" data-feather="sliders"></i><span class="align-middle">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <!-- <a class="sidebar-link" href="tenderHistory.php">
                        <i class="align-middle" data-feather="sliders"></i><span class="align-middle">Tender History</span>
                    </a> -->

                    <!-- <a class="sidebar-link collapsed" href="tenderHistory.php" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts"> -->
                    <a class="sidebar-link" href="tenderHistory.php">
                        <!-- Layouts -->
                        <div class="sb-sidenav-collapse-arrow"><i class="align-middle" data-feather="trending-up"></i><span class="align-middle">Tender History</span></div>
                    </a>
                    <!-- <div class="sidebar-link collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <select id="tenders" class="sidebar-link">
                                <option selected="selected">Select Your Tender</option> -->
                    <?php

                    // $qry = "SELECT msd_tender_no FROM `mms_suptender_details` WHERE msd_supplier_code = '$suppliercode'";
                    // $tenderList = mysqli_query($con, $qry)  or die("database error:" . mysqli_error($con));
                    // while ($row = mysqli_fetch_array($tenderList)) {

                    // $sql = "SELECT id, employee_name, employee_salary, employee_age FROM employee LIMIT 10";
                    // $resultset = mysqli_query($conn, $sql) or die("database error:" . mysqli_error($conn));
                    // while ($rows = mysqli_fetch_assoc($resultset)) {
                    ?>
                    <!-- <option value="<?= $row["msd_tender_no"]; ?>"><?= $row["msd_tender_no"]; ?></option> -->
                    <?php
                    // }
                    ?>
                    <!-- </select>
                        </nav>
                    </div> -->
                </li>
            </ul>

            <div class="sidebar-cta">
                <div class="sidebar-cta-content">
                    <div class="d-grid">
                        <a class="btn btn-primary" onclick="logoutfunction()">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Logout js -->
    <script>
        function logoutfunction() {
            let text = "Please Confirm To Logout!!";
            if (confirm(text) == true) {
                window.location = "logout.php";
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            // code to get all records from table via select box
            $("#tenders").change(function() {
                var msd_tender_no = $(this).find(":selected").val();
                var dataString = 'empid=' + msd_tender_no;
                $.ajax({
                    url: 'getTenderHistory.php',
                    dataType: "json",
                    data: dataString,
                    cache: false,

                    success: function(employeeData) {
                        if (employeeData) {
                            $("#heading").show();
                            $("#no_records").hide();
                            $("#emp_name").text(employeeData.employee_name);
                            $("#emp_age").text(employeeData.employee_age);
                            $("#emp_salary").text(employeeData.employee_salary);
                            $("#records").show();
                        } else {
                            $("#heading").hide();
                            $("#records").hide();
                            $("#no_records").show();
                        }
                    }
                });
            })
        });
    </script>
</body>

</html>