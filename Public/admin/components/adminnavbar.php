<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>
    <a href="" style="color: blue; font-weight: bolder; text-decoration: none;">
        HELLO <?php echo $_SESSION['name']; ?>! WELCOME TO eSupplier-CDPLC ADMIN DASHBOARD!!!
    </a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                <i class="align-middle" data-feather="settings"></i>
            </a>

            <a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                <img src="../../static/img/avatars/avatar1.jpg" class="avatar img-fluid rounded me-1" alt="User" /> <span class="text-dark"><?php echo $_SESSION['name']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="#" onclick="logoutfunction()">Log out</a>
            </div>
        </ul>
    </div>
</nav>

<script>
    function logoutfunction() {
        let text = 'Please Confirm To Logout!!';
        if (confirm(text) === true) {
            window.location = 'logoutadmin.php';
        }
    }
</script>
