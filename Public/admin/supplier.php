<?php
session_start();

require_once '../../backend/common/config.php';
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
	<link rel="shortcut icon" href="../../static/img/2.svg" />
	<title>eSupplier-CDL</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<link href="../../static/css/app.css" rel="stylesheet">
	<link href="../../static/css/main.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">



	<script src="../../static/js/jquery-3.3.1.min.js"></script>
	<script src="../../static/js/jquery.validate.min.js"></script>
	<script src="../../static/js/jquery.validate.unobtrusive.min.js"></script>

	<script src="../../static/js/app.js"></script>

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
		<?php include 'components/adminsidenav.php'; ?>
		<div class="main">
			<?php include 'components/adminnavbar.php'; ?>

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

			<?php include 'components/adminfooter.php'; ?>
</div>
</div>

</body>


</html>