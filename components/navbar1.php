<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <link rel="shortcut icon" href="./static/img/9.png" />

    <title>eSupplier-CDPLC</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" /> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="./static/css/app.css" rel="stylesheet">
    <link href="./static/css/main.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand navbar-light navbar-bg">
        <a class="sidebar-toggle js-sidebar-toggle">
            <i class="hamburger align-self-center"></i>
        </a>
        <a style="color: blue; font-weight: bolder; text-decoration: none;">
            <b>HELLO <?php echo $_SESSION['sup_name'] ?>! WELCOME TO eSupplier-CDPLC!</b>
        </a>

        <!-- timer -->
        <div id="timer" hidden></div>
        <!-- <div id="google_translate_element"></div> -->
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                        <i class="align-middle" data-feather="settings"></i>
                    </a>
                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <img src="./static/img/avatars/avatar1.jpg" class="avatar img-fluid rounded me-1" alt="" /> <span class="text-dark"><?php echo $_SESSION['sup_name'] ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="profile.php"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                        <!-- <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" onclick="logoutfunction()">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- <script src="js/translate.js"></script>
<script type="text/javascript" type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> -->

    <script src="static/js/app.js"></script>
    <!-- Logout js -->
    <script>
        function logoutfunction() {
            let text = "Please Confirm To Logout!!";
            if (confirm(text) == true) {
                window.location = "logout.php";
            }
        }
    </script>
</body>

</html>