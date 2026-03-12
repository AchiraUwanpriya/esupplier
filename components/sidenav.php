<?php
// $sbase is set by the parent file to prefix all relative URLs.
// Default to '' (empty) when included from root dashboard.php.
$sbase = isset($sbase) ? $sbase : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="<?= $sbase ?>static/img/9.png" />
    <title>eSupplier-CDPLC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="<?= $sbase ?>static/css/app.css" rel="stylesheet">
    <link href="<?= $sbase ?>static/css/main.css" rel="stylesheet">
</head>

<body>
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="dashboard.php">
                <center>
                    <img src="<?= $sbase ?>static/img/8.png" class="mt-3" style="width: 100%; padding-right: 30px;" alt="">
                </center>
            </a>
            <ul class="sidebar-nav">
                <li class="sidebar-header">Pages</li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="dashboard.php">
                        <i class="align-middle" data-feather="sliders"></i><span class="align-middle">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= $sbase ?>tenderHistory.php">
                        <div class="sb-sidenav-collapse-arrow"><i class="align-middle" data-feather="trending-up"></i><span class="align-middle">Tender History</span></div>
                    </a>
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

    <script>
        function logoutfunction() {
            let text = "Please Confirm To Logout!!";
            if (confirm(text) == true) {
                window.location = "<?= $sbase ?>logout.php";
            }
        }
    </script>
</body>

</html>