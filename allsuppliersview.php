<?php
session_start();

$entry = $_SESSION['entry'];

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
	header('Location: admin.php');
	exit();
}
?>
<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['insert'])) {

		if ($_POST['insert'] == 'vegitables' || 'spices' || 'fish' || 'dfish' || 'rice'|| 'pvcs' || 'med' ||'cab') {

			$rowCount = count($_POST['MMC_DESCRIPTION']);

			for ($x = 0; $x <= ($rowCount - 1); $x++) {

				$MMC_DESCRIPTION = $_POST['MMC_DESCRIPTION'][$x];
				$MMC_UNIT = $_POST['MMC_UNIT'][$x];

				$MMC_PRICE = $_POST['MMC_PRICE'] && $_POST['MMC_PRICE'][$x] ? $_POST['MMC_PRICE'][$x] : null;
				$MMC_MATERIAL_CODE = $_POST['MMC_MATERIAL_CODE'][$x];
				$MMC_CAT_CODE = $_POST['MMC_CAT_CODE'][$x];

				if (!$MMC_PRICE) {
					continue;
				}

				if ($con === false) {
					(print_r(mysqli_error($con), true));
				}
				$insert = "INSERT INTO mms_tenderprice_transactions(mtt_year,mtt_tender_no,mtt_supplier_code,mtt_material_code,mtt_price,mtt_status) VALUES ((SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A'), (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A'), 'sup1', ?, ?, 'A')";

				$params = array($MMC_MATERIAL_CODE, $MMC_PRICE);

				$stmt = mysqli_query($con, $insert, $params);
				if ($stmt === false) {
					(print_r(mysqli_error($con), true));
				} else {
				}
			}
			mysqli_close($con);
		}

		foreach ($_POST['MMC_DESCRIPTION'] as $row) {
			foreach ($_POST['MMC_MATERIAL_CODE'] as $row) {
				foreach ($_POST['MMC_CAT_CODE'] as $row) {
				}
			}
		}
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

	<title>eSupplier-CDL</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<link href="./static/css/app.css" rel="stylesheet">
	<link href="./static/css/main.css" rel="stylesheet">
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

	<script src="./static/js/jquery-3.3.1.min.js"></script>
	<script src="./static/js/jquery.validate.min.js"></script>
	<script src="./static/js/jquery.validate.unobtrusive.min.js"></script>

	<script src="./static/js/app.js"></script>

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

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

	<script>
		function popupmsg() {
			alert("Supplier has approved!");
			window.location.reload();
		}
	</script>

	<script>
		function logoutfunction() {
			alert("Please Confirm To Logout!!");
		}

		function confirmDelete(supplierCode) {
			if (confirm("Do you want to delete the user?")) {
				document.getElementById('supplierCode').value = supplierCode;
				document.getElementById('deleteForm').submit();
			}
		}
	</script>

</head>

<body>
	<div class="wrapper">
		<?php include './Admin_components/adminsidenav.php' ?>

		<div class="main">
			<?php include './Admin_components/adminnavbar.php' ?>

			<!-- dashboard content -->
			<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3">
						<strong>
							Pending Suppliers
						</strong>
					</h1>

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
												<?php
												if ($entry == 'N') {
													$viewButtonDisabled = true;
												} else {
													$viewButtonDisabled = false;
												}
												$tsql = "SELECT *, 
															CASE 
															WHEN msd_supply_category = 'RI' THEN 'Ration Items'
															WHEN msd_supply_category = 'PI' THEN 'Pvc Items'
															ELSE msd_supply_category 
															END AS msd_supply_category 
														FROM mms_supplier_pending_details 
														WHERE msd_status = 'I' 
														ORDER BY msd_supplier_code DESC;";
												$stmt = mysqli_query($con, $tsql);
												if ($stmt === false) {
													echo "Error in query";
													die(print_r(mysqli_error($con), true));
												}
												$index = 0;
												while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
												?>
													<tr>
														<td><?php echo $row['msd_supplier_code']; ?><input type="text" hidden name="msd_supplier_code[<?= $index ?>]" value="<?php echo $row['msd_supplier_code']; ?>"></td>
														<td><?php echo $row['msd_supplier_name']; ?><input type="text" hidden name="msd_supplier_name[<?= $index ?>]" value="<?php echo $row['msd_supplier_name']; ?>"></td>
														<td><?php echo $row['msd_email_address']; ?><input type="text" hidden name="msd_email_address[<?= $index ?>]" value="<?php echo $row['msd_email_address']; ?>"></td>
														<td><?php echo $row['msd_mobileno']; ?><input type="number" hidden name="msd_mobileno[<?= $index ?>]" value="<?php echo $row['msd_mobileno']; ?>"></td>
														<td><?php echo $row['msd_supply_category']; ?><input type="text" hidden name="msd_supply_category[<?= $index ?>]" value="<?php echo $row['msd_supply_category']; ?>"></td>
														<td><?php echo $row['msd_address']; ?><input type="text" hidden name="msd_address[<?= $index ?>]" value="<?php echo $row['msd_address']; ?>"></td>
														<td>
															<div class="form-check">
																<input <?php if ($viewButtonDisabled) echo 'disabled'; ?> type="checkbox" class="form-check-input" id="exampleCheck1" name="exampleCheck1" <?= $row['msd_status'] === "A" ? "checked" : "" ?> onClick="test(this,'<?= $row['msd_supplier_code'] ?>','<?= $row['msd_supplier_name'] ?>','<?= $row['msd_email_address'] ?>','<?= $row['msd_mobileno'] ?>','<?= $row['msd_supply_category'] ?>','<?= $row['msd_address'] ?>','<?= $row['msd_supply_category_des'] ?>');">
																<label class="form-check-label fw-bold text-success" for="">Approve</label>
															</div>
														</td>
														<td>
															<form method="POST" action="delete_supplier.php" onsubmit="return confirm('Do you want to delete the supplier?');">
																<input type="hidden" name="supplierCode" value="<?php echo $row['msd_supplier_code']; ?>">
																<button type="submit" class="btn btn-danger">Delete</button>
															</form>
														</td>
													</tr>
												<?php
													$index++;
												}
												?>
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
			<?php include './components/footer.php' ?>
		</div>
	</div>
	<script>
		function logoutfunction() {
			alert("Please Confirm To Logout!!");
		}

		function test(e, msd_supplier_code, msd_supplier_name, msd_email_address, msd_mobileno, msd_supply_category, msd_address, msd_supply_category_des) {
			// Prevent the default checkbox behavior temporarily
			e.preventDefault ? e.preventDefault() : (event.returnValue = false);
			
			if (e.checked) {
				// Confirm approval
				if (!confirm("Are you sure you want to approve this supplier?")) {
					return false;
				}
			}

			var url = "ajaxservice.php?func=changesuppstatus&suppliercode=" + encodeURIComponent(msd_supplier_code) +
				"&msd_supplier_name=" + encodeURIComponent(msd_supplier_name) +
				"&msd_email_address=" + encodeURIComponent(msd_email_address) +
				"&msd_mobileno=" + encodeURIComponent(msd_mobileno) +
				"&msd_supply_category=" + encodeURIComponent(msd_supply_category) +
				"&msd_address=" + encodeURIComponent(msd_address) +
				"&msd_supply_category_des=" + encodeURIComponent(msd_supply_category_des) +
				"&supplieraction=" + e.checked;

			console.log("Approval request:", url);

			$.ajax({
				url: url,
				type: 'GET',
				timeout: 10000,
				success: function(data, status) {
					console.log("Response:", data);
					alert("Supplier has been " + (e.checked ? "approved" : "rejected") + " successfully!");
					window.location.reload();
				},
				error: function(xhr, status, error) {
					console.error("Error:", error, xhr.responseText);
					alert("Error processing supplier approval. Please try again. Error: " + error);
					// Uncheck the checkbox if there was an error
					e.checked = !e.checked;
				}
			});
		}
	</script>
</body>

</html>