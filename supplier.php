<?php
session_start();

include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['insertbtn'])) {

		// $supcode = $_POST['supcode'];
		$supcode = time();
		$supname = $_POST['supname'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		// $createdby = $_SESSION["sup_code"];
		$createdby = $_SESSION['User'];
		$createddate = date('Y-m-d');



		$query = "INSERT INTO mms_supplier_pending_details (msd_supplier_code,msd_supplier_name,msd_email_address,msd_mobileno,msd_status,created_by,created_date) VALUES ('$supcode','$supname', '$email','$mobile','I','$createdby','$createddate')";
		// $query_run = sqlsrv_query($con, $query);
		$query_run = mysqli_query($con, $query);
		if ($query_run) {
			echo "<script>alert('Records Saved Successfully!!'); </script>";
		} else {
			echo "<script>alert('ERROR!'); </script>";
			//echo json_encode(sqlsrv_errors());
			//die;
			die("database error:" . mysqli_error($con));
		}

		//    if($query_run) {
		//     echo "Successfully Added your details!";

		//   }
		//   else {
		//     echo "Please fill the fields!";
		//   }
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="./static/img/2.svg" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/" />

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

	<script>
		function myFunctionVeg() {
			alert("Data Saved Successfully!!!");
		}
	</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>


</head>

<body>
	<!-- <script>
if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script> -->

	<div class="wrapper">
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
					<!-- <li class="sidebar-item active" >
						<a class="sidebar-link">
							<i class="align-middle" data-feather="sliders"></i><span class="align-middle">Supplier Managment</span>
						</a>
					</li> -->

					<li class="sidebar-item">
						<a class="sidebar-link" href="allsuppliersview.php">
							<i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Pending Suppliers</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="allactivesuppliersview.php">
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Registered Suppliers</span>
						</a>
					</li>

					<!-- <li class="sidebar-item active">
						<a class="sidebar-link" href="">
							<i class="align-middle" data-feather="twitch"></i><span class="align-middle">Tender Managment</span>
						</a>
					</li> -->
					<li class="sidebar-header">
						Tender Managment
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="tenderview.php">
							<i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Tenders</span>
						</a>
					</li>
					<li class="sidebar-item ">
						 <a class="sidebar-link" href="monthlytenderview.php">
							<i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Monthly Tenders</span>
						</a>
					</li>

					<li class="sidebar-header">
						Food Managment
					</li>
					<!-- <li class="sidebar-item active">
						<a class="sidebar-link" href="">
							<i class="align-middle" data-feather="slack"></i><span class="align-middle">Food Managment</span>
						</a>
					</li> -->

					<li class="sidebar-item">
						<a class="sidebar-link" href="Admin_components/addfood.php">
							<i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Add Food</span>
						</a>
					</li>

					<!-- <li class="sidebar-item">
						<a class="sidebar-link" href="index.html">
					<i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Sign Up</span>
					</a>
					</li> -->




					<!-- <li class="sidebar-header">
						Tools & Components
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="ui-buttons.html">
							<i class="align-middle" data-feather="square"></i> <span class="align-middle">Buttons</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="ui-forms.html">
              				<i class="align-middle" data-feather="check-square"></i> <span class="align-middle">Forms</span>
            			</a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="icons-feather.html">
							<i class="align-middle" data-feather="coffee"></i> <span class="align-middle">Icons</span>
						</a>
					</li> -->
				</ul>

				<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<!-- <strong class="d-inline-block mb-2">Upgrade to Pro</strong>
						<div class="mb-3 text-sm">
							Are you looking for more components? Check out our premium version.
						</div> -->
						<div class="d-grid">
							<a href="admin.php" class="btn btn-primary" onclick="logoutfunction()">Logout</a>

						</div>
					</div>
				</div>
			</div>
		</nav>
		<script>
			function logoutfunction() {
				alert("Please Confirm To Logout!!");
			}
		</script>
		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<!-- <li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator">4</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									4 New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-danger" data-feather="alert-circle"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Update completed</div>
												<div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
												<div class="text-muted small mt-1">30m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-warning" data-feather="bell"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Lorem ipsum</div>
												<div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-primary" data-feather="home"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Login from 192.186.1.8</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-success" data-feather="user-plus"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">New connection</div>
												<div class="text-muted small mt-1">Christina accepted your request.</div>
												<div class="text-muted small mt-1">14h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all notifications</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="message-square"></i>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
								<div class="dropdown-menu-header">
									<div class="position-relative">
										4 New Messages
									</div>
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Vanessa Tucker">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Vanessa Tucker</div>
												<div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
												<div class="text-muted small mt-1">15m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="William Harris">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">William Harris</div>
												<div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Christina Mason">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Christina Mason</div>
												<div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
												<div class="text-muted small mt-1">4h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Sharon Lessman">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Sharon Lessman</div>
												<div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all messages</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown"> -->
						<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
							<i class="align-middle" data-feather="settings"></i>
						</a>

						<a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
							<img src="./static/img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1" alt="TestUser" /> <span class="text-dark">TestUser</span>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
							<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
							<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="admin.php">Log out</a>
						</div>
						</li>
					</ul>
				</div>
			</nav>

			<!-- dashboard content -->
			<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3">
						<strong>
							eSupplier Add
						</strong>
					</h1>

					<!-- Toast -->
					<!-- <div class="container mt-5"> -->

					<!-- button to initialize toast -->
					<!-- <button type="button" class="btn btn-primary" id="toastbtn">Initialize toast</button> -->

					<!-- Toast -->
					<!-- <div class="toast">
  <div class="toast-header">
	<strong class="mr-auto">Bootstrap</strong>
	<small>11 mins ago</small>
	<button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
	  <span>&times;</span>
	</button>
  </div>
  <div class="toast-body">
	Hello, world! This is a toast message.
  </div>
</div>

</div> -->


					<!-- Popper.js first, then Bootstrap JS -->
					<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
<script>
document.getElementById("insert").onclick = function() {
  var toastElList = [].slice.call(document.querySelectorAll('.toast'))
  var toastList = toastElList.map(function(toastEl) {
  // Creates an array of toasts (it only initializes them)
	return new bootstrap.Toast(toastEl) // No need for options; use the default options
  });
 toastList.forEach(toast => toast.show()); // This show them

  console.log(toastList); // Testing to see if it works
};

</script> -->

</body>

</html>

<!-- <form id="supplier" name="supplier" method="POST" onsubmit="return validateForm()" required> -->
<form id="supplier" name="supplier" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<!-- <div class="form-group">
		<label for="exampleFormControlInput1">Supplier Code</label>
		<input type="text" class="form-control" name="supcode" id="supcode" placeholder="Supplier Code" >
	</div> -->

	<div class="form-group">
		<label for="exampleFormControlInput1">Supplier Name</label>
		<input type="text" class="form-control" name="supname" id="supname" placeholder="Supplier Name" required>
	</div>

	<div class="form-group">
		<label for="exampleFormControlInput1">Email Address</label>
		<input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
	</div>

	<div class="form-group">
		<label for="exampleFormControlInput1">Mobile Number</label>
		<input type="number" class="form-control" name="mobile" id="mobile" placeholder="Mobile Number" required>
	</div>

	<div class="modal-footer">
		<input type="hidden" name="insert" value="rice" />
		<a href="allsuppliersview.php">
			<button type="button" class="btn btn-primary">Back</button>
		</a>
		<!-- <button type="submit" name="insertbtn" id="insertbtn"  class="btn btn-success" onclick=" savefunction()"  >Save changes </button> -->

		<!-- <button type="submit" name="insertbtn" id="insertbtn"  class="btn btn-success">Save changes </button> -->
		<!-- <button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick=" savefunction()" >Save changes </button> -->
		<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success">Save changes </button>
		<!-- <input type="submit" name="submit" value="Submit">  -->

	</div>
</form>

<!-- <script>
function savefunction() {
  alert("Records Saved Successfully!!");
  
}
</script>	 -->
<!-- <script>
function validateForm() {
  var x = document.forms["supplier"]["supcode"].value;
  if (x == "" || x == null) {
    alert("All Fields must be filled out");
	
    return false;
  }
}
</script> -->

<footer class="footer">
	<div class="container-fluid">
		<div class="row text-muted">
			<div class="col-6 text-start">
				<p class="mb-0">
					<a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>
							&copy;
							<script>
								new Date().getFullYear()
							</script>
							Copyright: <a href="https://www.dockyardsolutions.lk">     Dockyard Total Solutions (Pvt)Ltd </a>
						</strong></a>
				</p>
			</div>
			<div class="col-6 text-end">
				<ul class="list-inline">
					<li class="list-inline-item">
						<a class="text-muted" href="https://adminkit.io/" target="_blank">Support</a>
					</li>
					<li class="list-inline-item">
						<a class="text-muted" href="https://adminkit.io/" target="_blank">Help Center</a>
					</li>
					<li class="list-inline-item">
						<a class="text-muted" href="https://adminkit.io/" target="_blank">Privacy</a>
					</li>
					<li class="list-inline-item">
						<a class="text-muted" href="https://adminkit.io/" target="_blank">Terms</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</footer>
</div>
</div>

</body>


</html>