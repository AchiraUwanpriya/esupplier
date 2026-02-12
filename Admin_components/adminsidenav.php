<?php
// session_start();

$entry = $_SESSION['entry'];

// if ($entry == 'N') {
//     $ButtonsDisabled = true;
// } else {
//     $ButtonsDisabled = false;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="./static/img/2.svg" />

    <title>eSupplier-CDL</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="./static/css/app.css" rel="stylesheet">
    <link href="./static/css/main.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <script src="./static/js/jquery-3.3.1.min.js"></script>
    <script src="./static/js/jquery.validate.min.js"></script>
    <script src="./static/js/jquery.validate.unobtrusive.min.js"></script>

    <script src="./static/js/app.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</head>


<body>
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="adminview.php">
                <!-- <span class="align-middle">eSupplier-CDL</span> -->
                <center><img src="./static/img/8.png" class="mt-3" style=" width: 100%; padding-right: 30px;" alt=""></center>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Supplier Managment
                </li>

                <li class="sidebar-item " id="pending-suppliers">
                    <a class="sidebar-link" href="allsuppliersview.php">
                        <i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Pending Suppliers</span>
                    </a>
                </li>
                <li class="sidebar-item " id="registered-suppliers">
                    <a class="sidebar-link" href="allactivesuppliersview.php">
                        <i class="align-middle" data-feather="users"></i> <span class="align-middle">Registered Suppliers</span>
                    </a>
                </li>
                <li class="sidebar-header">
                    Tender Managment
                </li>
                <li class="sidebar-item " id="tenders">
                    <a class="sidebar-link" href="tenderview.php">
                        <i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Tenders</span>
                    </a>
                </li>
                <li class="sidebar-item ">
						 <a class="sidebar-link" href="monthlytenderview.php">
							<i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Monthly Tenders</span>
						</a>
					</li>
                <?php if ($entry != 'N') : ?>
                    <li class="sidebar-header">
                        Food Managment
                    </li>
                    <li class="sidebar-item" id="add-food">
                        <a class="sidebar-link" href="addfood.php">
                            <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Add Food</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="sidebar-cta">
                <div class="sidebar-cta-content">
                    <div class="d-grid">
                        <a href="admin.php" class="btn btn-primary" onclick="logoutfunction()">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</body>

<script>
    $(document).ready(function() {
        var url = window.location.pathname;
        var activePage = url.substring(url.lastIndexOf('/') + 1);

        if (activePage == 'allsuppliersview.php') {
            $('#pending-suppliers').addClass('active');
        } else if (activePage == 'allactivesuppliersview.php') {
            $('#registered-suppliers').addClass('active');
        } else if (activePage == 'tenderview.php') {
            $('#tenders').addClass('active');
        } else if (activePage == 'addfood.php') {
            $('#add-food').addClass('active');
        }
    });
</script>

<!-- Logout js -->
<script>
    function logoutfunction() {
        let text = "Please Confirm To Logout!!";
        if (confirm(text) == true) {
            window.location = "logoutadmin.php";
        }
    }
</script>

</html>