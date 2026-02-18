<?php
$entry = $_SESSION['entry'] ?? 'N';
?>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="adminview.php">
            <center><img src="../../static/img/8.png" class="mt-3" style="width: 100%; padding-right: 30px;" alt=""></center>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">Supplier Managment</li>

            <li class="sidebar-item" id="pending-suppliers">
                <a class="sidebar-link" href="allsuppliersview.php">
                    <i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Pending Suppliers</span>
                </a>
            </li>
            <li class="sidebar-item" id="registered-suppliers">
                <a class="sidebar-link" href="../../allactivesuppliersview.php">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Registered Suppliers</span>
                </a>
            </li>

            <li class="sidebar-header">Tender Managment</li>
            <li class="sidebar-item" id="tenders">
                <a class="sidebar-link" href="../../tenderview.php">
                    <i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Tenders</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="../../monthlytenderview.php">
                    <i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Monthly Tenders</span>
                </a>
            </li>

            <?php if ($entry != 'N') : ?>
                <li class="sidebar-header">Food Managment</li>
                <li class="sidebar-item" id="add-food">
                    <a class="sidebar-link" href="../../addfood.php">
                        <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Add Food</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <div class="d-grid">
                    <a href="../../admin.php" class="btn btn-primary" onclick="logoutfunction()">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function() {
        var url = window.location.pathname;
        var activePage = url.substring(url.lastIndexOf('/') + 1);

        if (activePage === 'allsuppliersview.php') {
            $('#pending-suppliers').addClass('active');
        } else if (activePage === 'allactivesuppliersview.php') {
            $('#registered-suppliers').addClass('active');
        } else if (activePage === 'tenderview.php') {
            $('#tenders').addClass('active');
        } else if (activePage === 'addfood.php') {
            $('#add-food').addClass('active');
        }
    });

    function logoutfunction() {
        let text = 'Please Confirm To Logout!!';
        if (confirm(text) === true) {
            window.location = '../../logoutadmin.php';
        }
    }
</script>
