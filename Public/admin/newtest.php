<?php
session_start();
// if (!isset($_SESSION['msd_supplier_name'])) {
// }
?>
<?php
include '../../config.php';

if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
	header('Location: admin.php');
	exit();
}

$entry = $_SESSION['entry'];
?> 

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


	<!-- Modal Update Script -->

	<script>
		function myFunctionVeg() {
			alert("Data Inserted Successfully!!!");
		}

		function selectProduct(meterialCode, desc, spec, unit, sts) {
			//console.log(meterialCode,desc, spec);
			$("input#MaterialCode").val(meterialCode)
			$('input#Description').val(desc)
			$('input#MaterialSpec').val(spec)
			$('input#Unit').val(unit)
			$('input#stsactive').attr('checked', sts)
			$('input#stsinactive').attr('checked', !sts)
		}
	</script>

	<!-- <?= $row['msd_status'] === "A" ? "checked" : "" ?> -->


	<!-- Modal Update Script End -->

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

</head>

<body>
	<div class="wrapper">
		<?php include 'components/adminsidenav.php'; ?>
		<div class="main">
			<?php include 'components/adminnavbar.php'; ?>

			<!-- dashboard content -->
			<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3">
						<strong>
							eSupplier Add Food
						</strong>
					</h1>

					<!-- Toast -->

					<div class="row" style="align-items: center; padding-left: 60px; padding-top:80px">

						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
								<img class="card-img-top pt-4" src="./static/img/vegetable.png" style="width: 80px; " alt="Card image cap ">
								<div class="card-body">
									<h5 class="card-title" style="text-decoration-line: none;">VEGETABLE ITEMS TENDER</h5>
								</div>
							</a>

						</div>

						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0" style="width: 18rem;">

							<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable1">
								<img class="card-img-top pt-4" src="./static/img/spice.png" style="width: 80px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">SPICES TENDER</h5>
								</div>
							</a>
						</div>

						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable2">
								<img class="card-img-top pt-4" src="./static/img/fish.png" style="width: 80px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">FISH TENDER</h5>
								</div>
							</a>
						</div>

						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable3">
								<img class="card-img-top pt-4" src="./static/img/dried-fish.png" style="width: 80px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">DRY FISH TENDER</h5>
								</div>
							</a>
						</div>

						<!-- <div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#cocoilcreamModal">
								<img class="card-img-top pt-4" src="./static/img/coconut-oil.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">COCONUT OIL & COCONUT CREAMER</h5>
								</div>
							</a>
						</div> -->
					</div>

					<!-- ============= -->
					<div class="row" style="align-items: center; padding-left: 60px;">
						<!-- dry items -->
						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#dryItemsModal">
								<img class="card-img-top pt-4" src="./static/img/dried-item.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">DRY ITEMS</h5>
								</div>
							</a>
						</div>

						<!-- coconut -->
						<!-- <div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#coconutModal">
								<img class="card-img-top pt-4" src="./static/img/coconut.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">COCONUT</h5>
								</div>
							</a>
						</div> -->

						<!-- eggs -->
						<!-- <div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#eggsModal">
								<img class="card-img-top pt-4" src="./static/img/eggs.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">EGGS</h5>
								</div>
							</a>
						</div> -->

						<!-- rice -->
						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#riceModal">
								<img class="card-img-top pt-4" src="./static/img/rice.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">RICE</h5>
								</div>
							</a>
						</div>

						<!-- chicken -->
						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#chickenModal">
								<img class="card-img-top pt-4" src="./static/img/chicken-leg.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">MEAT</h5>
								</div>
							</a>
						</div>

						<!-- wrapping papers -->
						<!-- <div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#wrappingpModal">
								<img class="card-img-top pt-4" src="./static/img/gift-wrapping.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">WRAPPING PAPERS</h5>
								</div>
							</a>
						</div> -->

						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#wrappingpModal">
								<img class="card-img-top pt-4" src="./static/img/gift-wrapping.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">MISCELLANEOUS ITEMS</h5>
								</div>
							</a>
						</div>
					</div>


					<!-- ============= -->
					<div class="row" style="align-items: center; padding-left: 60px;">
						<!-- dry items -->
						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#pvcModal">
								<img class="card-img-top pt-4" src="./static/img/Pvc.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">PVC ITEMS</h5>
								</div>
							</a>
						</div>

						

						<!-- rice -->
						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#medicineModal">
								<img class="card-img-top pt-4" src="./static/img/medicine.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">MEDICINE ITEMS</h5>
								</div>
							</a>
						</div>

						<!-- chicken -->
						<div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#cableModal">
								<img class="card-img-top pt-4" src="./static/img/cables.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">CABLES</h5>
								</div>
							</a>
						</div>

						<!-- wrapping papers -->
						<!-- <div class="card rounded shadow-sm col-lg-3 col-md-6 mb-4 mb-lg-0 bg-light" style="width: 18rem;">
							<a href="" data-bs-toggle="modal" data-bs-target="#wrappingpModal">
								<img class="card-img-top pt-4" src="./static/img/gift-wrapping.png" style="width: 68px;" alt="Card image cap">
								<div class="card-body">
									<h5 class="card-title">WRAPPING PAPERS</h5>
								</div>
							</a>
						</div> -->


						

						
					</div>

					<!-- </fieldset> -->
					<!-- <fieldset>
					<table class="table table-borderless">
						<tr>
							<th ><h1>Description/විස්තර</h1></th>
							<th><h1>Unit/ඒකකය: KG</h1></th>
							<th><h1>Price/මිල (RS.)</h1></th>
						</tr>

						<?php
						$tsql = "SELECT *
						FROM mms_material_catalogue";
						// $stmt = sqlsrv_query($con,$tsql);
						$stmt = mysqli_query($con, $tsql);
						if ($stmt === false) {
							echo "Error in query";
							// die(print_r(sqlsrv_errors(),true));
							die(print_r(mysqli_error($con), true));
						}

						// while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
						while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
						?>
							<tr>
								<th><?php echo $row['MMC_DESCRIPTION']; ?></th>
								<th><?php echo $row['MMC_UNIT']; ?></th>
								<th>
								<input class="mt-4" style="max-width:100px" type="number" name="MMC_PRICE" id="MMC_PRICE" placeholder="Price" />
								</th>
						</tr>
						<?php
						}
						?>
					</table>
						
							<input type="submit" name="insert" id="insert" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Add New" />
							<button type="submit" name="insert" id="insert" class="next action-button" value="Next" >test</button>

						</fieldset> -->

					</form>
				</div>
			</main>
			<!-- footer -->
			<?php include 'components/adminfooter.php'; ?>
		</div>
	</div>
	</form>

	<!-- UPDATE MODAL -->
	<?php
	include '../../config.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['updatebtn'])) {

			//   $MaterialCode = $_POST['MaterialCode'];
			//   $Description = $_POST['Description'];
			//   $MaterialSpec = $_POST['MaterialSpec'];

			//$sup_code = $_SESSION["sup_code"];
			$sup_code = $_SESSION['User'];
			$date_now = date('Y-m-d');
			// if($rows !== null && $rows['Description'] === $MMC_DESCRIPTION && $rows['MaterialSpec'] === $MMC_MATERIAL_SPEC && $rows['Status'] === $MMC_STATUS){
			// continue;
			// }
			$query = "UPDATE mms_material_catalogue SET MMC_DESCRIPTION='" . $_POST['Description'] . "', MMC_MATERIAL_SPEC='" . $_POST['MaterialSpec'] . "', MMC_STATUS='" . $_POST['Status'] . "'
							,updated_by='$sup_code',updated_date='$date_now' WHERE MMC_MATERIAL_CODE='" . $_POST['MaterialCode'] . "'";

			//  $query_run = sqlsrv_query($con,$query);
			$query_run = mysqli_query($con, $query);

			if ($query_run) {
				echo "Data Updated Successfully!";
			} else {
				echo "Error";
			}
		}
	}
	?>

	<?php

	include '../../config.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['insertbtn'])) {

			$MaterialCode = $_POST['MaterialCode'];
			$Description = $_POST['Description'];
			$MaterialSpec = $_POST['MaterialSpec'];
			$BalanceQty = $_POST['BalanceQty'];
			$StockCat = $_POST['StockCat'];
			$BinLocation = $_POST['BinLocation'];
			$Unit = $_POST['Unit'];




            $query = "INSERT INTO mms_material_catalogue (MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_BALANCE_QTY,MMC_STOCK_CATEGORY,MMC_BIN_LOCATION,MMC_UNIT) 
			VALUES (' $MaterialCode','$Description', '$MaterialSpec','$BalanceQty','$StockCat','$BinLocation','$Unit')";			// $query_run = sqlsrv_query($con,$query);
			$query_run = mysqli_query($con, $query);

			if ($query_run) {
				echo "Data Inserted Successfully!";
			} else {
				echo "Error";
			}
		}
	}
	?>


	<!-- Food Update Modal - for all categories -->

	<div class="modal fade" id="exampleModalScrollableupdate" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">UPDATE DETAILS</h2>


				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" value="Material Code" hidden>
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" value="Material Code" disabled>
									</div>
									<div class="form-group row">
										<label for="Description" class="col-sm-2 col-form-label">Description:</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
										</div>
									</div>
									<div class="form-group row">
										<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
										</div>
									</div>
									<div class="form-group row">
										<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
										<div class="col-sm-10">
											<input type="text" class="form-control-plaintext" name="Unit" id="Unit" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label for="Status" class="col-sm-2 col-form-label">Status:</label>
										<div class="col-sm-10">
											<input type='Radio' name='Status' value='A' id="stsactive">Active
											<input type='Radio' name='Status' value='I' id="stsinactive">Inactive
										</div>
									</div>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="updatebtn" id="updatebtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>

		</div>
	</div>

	</form>
	<!-- END UPDATE MODAL FOODS -->


	<!-- Modal Veg -->
	<div class="modal fade" id="exampleModalScrollable" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
			<div class="modal-content" data-backdrop="static">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">VEGETABLE ADD TENDER</h2>
					<img src="./static/img/vegetable.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>


							</tr>

							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('V')";
							// $stmt = sqlsrv_query($con,$tsql);
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								// die(print_r(sqlsrv_errors(),true));
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							// while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<!-- UPDATE BUTTON -->
									<th>

										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>
										</a>
									</th>
									<!-- UPDATE BUTTON -->
									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollablenew">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>
	</form>
	<!-- Add new modal -->

	<div class="modal fade" id="exampleModalScrollablenew" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">VEGETABLE ADD TENDER</h2>
					<img src="./static/img/vegetable.png" style="width: 80px;" alt="">


				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>

	<!-- End Modal -->

	<!-- Start Modal Spices -->
	<div class="modal fade" id="exampleModalScrollable1" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">SPICES ADD TENDER</h2>
					<img src="./static/img/spice.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>


							</tr>



							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('S')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>

									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="spices" />
					<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollablenew1">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>

	<!-- Add new, modal -->
	<div class="modal fade" id="exampleModalScrollablenew1" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">SPICES UPDATE TENDER</h2>
					<img src="./static/img/spice.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal -->


	<!-- Start Modal Fish -->
	<div class="modal fade" id="exampleModalScrollable2" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">FISH ADD TENDER</h2>
					<img src="./static/img/fish.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>


							</tr>



							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('F')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="fish" />
					<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollablenew2">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>

	<!-- Add new, modal -->
	<div class="modal fade" id="exampleModalScrollablenew2" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">FISH UPDATE TENDER</h2>
					<img src="./static/img/fish.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" name="Unit" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal -->


	<!--Start Modal DRY FISH-->
	<div class="modal fade" id="exampleModalScrollable3" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">DRY FISH ADD TENDER</h2>
					<img src="./static/img/dried-fish.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>


							</tr>


							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('D')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="dfish" />
					<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollablenew3">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>

	<!-- Add new, modal -->
	<div class="modal fade" id="exampleModalScrollablenew3" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">DRY FISH UPDATE TENDER</h2>
					<img src="./static/img/dried-fish.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal DRY FISH -->

	<!-- Start Modal Coconut Oil & Coconut Creamer -->
	<div class="modal fade" id="cocoilcreamModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">OIL & COCONUT CREAMER</h2>
					<img src="./static/img/coconut-oil.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>
							</tr>

							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('O')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="rice" />
					<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollablenew4">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>
	<!-- Add new, modal -->
	<div class="modal fade" id="exampleModalScrollablenew4" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">UPDATE OIL & COCONUT CREAMER</h2>
					<img src="./static/img/coconut-oil.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>

				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal Coconut Oil & Coconut Creamer-->


	<!-- new items adding ======================== -->

	<!-- Start Modal Dry items -->
	<div class="modal fade" id="dryItemsModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">DRY ITEMS</h2>
					<img src="./static/img/dried-item.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>
							</tr>

							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('Y')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>

									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>

									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php

								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="spices" />
					<a href="" data-bs-toggle="modal" data-bs-target="#dryItemsAddModal">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>

	<!-- Add new, modal -->
	<div class="modal fade" id="dryItemsAddModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">UPDATE DRY ITEMS</h2>
					<img src="./static/img/dried-item.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal Dry Items -->


	<!-- Start Modal Coconut -->
	<div class="modal fade" id="coconutModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">COCONUT</h2>
					<img src="./static/img/coconut.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>
							</tr>

							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('C')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>

									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php

								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="rice" />
					<a href="" data-bs-toggle="modal" data-bs-target="#coconutNewModal">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>
	<!-- Add new, modal COCONUT -->
	<div class="modal fade" id="coconutNewModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">UPDATE COCONUT</h2>
					<img src="./static/img/coconut.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>

				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal Coconut -->

	<!--Start Modal EGGS-->
	<div class="modal fade" id="eggsModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">EGGS LIST</h2>
					<img src="./static/img/eggs.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>


							</tr>


							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('E')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="dfish" />
					<a href="" data-bs-toggle="modal" data-bs-target="#eggsNewModal">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>

	<!-- Add new, modal -->
	<div class="modal fade" id="eggsNewModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">EGGS ITEMS UPDATE</h2>
					<img src="./static/img/eggs.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal EGGS -->

	<!--Start Modal RICE-->
	<div class="modal fade" id="riceModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">RICE LIST</h2>
					<img src="./static/img/rice.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>


							</tr>


							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('R')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="dfish" />
					<a href="" data-bs-toggle="modal" data-bs-target="#riceNewModal">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>

	<!-- Add new, modal -->
	<div class="modal fade" id="riceNewModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">RICE UPDATE</h2>
					<img src="./static/img/rice.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal rice -->


	<!-- Start Modal CHICKEN -->
	<div class="modal fade" id="chickenModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">MEAT LIST</h2>
					<img src="./static/img/chicken-leg.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>
							</tr>

							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('H')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php

								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="rice" />
					<a href="" data-bs-toggle="modal" data-bs-target="#chickenNewModal">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>
	<!-- Add new, modal -->
	<div class="modal fade" id="chickenNewModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">MEAT UPDATE</h2>
					<img src="./static/img/chicken-leg.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>

				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal CHICKEN-->

	<!-- Start Modal Wrapping papers TO Miscellaneous ITEMS -->
	<div class="modal fade" id="wrappingpModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">MISCELLANEOUS ITEMS</h2>
					<img src="./static/img/gift-wrapping.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
									</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
									</u></th>
							</tr>

							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('M')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?> <input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>
									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>



									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php




								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="rice" />
					<a href="" data-bs-toggle="modal" data-bs-target="#wrappingpNewModal">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>
	<!-- Add new, modal -->
	<div class="modal fade" id="wrappingpNewModal" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">Miscellaneous  Items Add</h2>
					<img src="./static/img/gift-wrapping.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text" readonly class="form-control-plaintext" id="Unit" value="KGS">
									</div>
								</div>


						</table>
				</div>

				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main modal wrapping papers -->

	<!-- Start Modal PVC Items-->
	<div class="modal fade" id="pvcModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">PVC ITEMS</h2>
					<img src="./static/img/Pvc.png" style="width: 100px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg" action="dashboard.php?stage=2" method="POST">
						<table class="table table-hover">
							<tr>
								<th><u>
										<h5 class="fw-bold">Material Code</h5 class="fw-bold">
								</u></th>
								<th><u>
										<h5 class="fw-bold">Description</h5 class="fw-bold">
								</u></th>	
								<th><u>
										<h5 class="fw-bold">Spec</h5 class="fw-bold">
								</u></th>			
								<th><u>
										<h5 class="fw-bold">Balance Quantity</h5 class="fw-bold">
								</u></th>
								<th><u>
										<h5 class="fw-bold">Stock Category</h5 class="fw-bold">
								</u></th>
								<th><u>
										<h5 class="fw-bold">Bin Location</h5 class="fw-bold">
								</u></th>
								<th><u>
										<h5 class="fw-bold">Unit</h5 class="fw-bold">
								</u></th>
								<th><u>
										<h5 class="fw-bold">Action</h5 class="fw-bold">
								</u></th>
							</tr>

							<?php
							$tsql = "SELECT MMC_MATERIAL_CODE,MMC_DESCRIPTION,MMC_MATERIAL_SPEC,MMC_UNIT,MMC_BALANCE_QTY, MMC_STOCK_CATEGORY, MMC_BIN_LOCATION,MMC_STATUS
								FROM mms_material_catalogue
								WHERE MMC_CAT_CODE in ('P')";
							$stmt = mysqli_query($con, $tsql);
							if ($stmt === false) {
								echo "Error in query";
								die(print_r(mysqli_error($con), true));
							}
							$index = 0;
							while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
							?>
								<tr>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_DESCRIPTION']; ?><input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_BALANCE_QTY']; ?> <input type="text" hidden name="MMC_BALANCE_QTY[<?= $index ?>]" value="<?php echo $row['MMC_BALANCE_QTY']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_STOCK_CATEGORY']; ?> <input type="text" hidden name="MMC_STOCK_CATEGORY[<?= $index ?>]" value="<?php echo $row['MMC_STOCK_CATEGORY']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_BIN_LOCATION']; ?> <input type="text" hidden name="MMC_BIN_LOCATION[<?= $index ?>]" value="<?php echo $row['MMC_BIN_LOCATION']; ?>"></h6>
									</th>
									<th>
										<h6><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></h6>
									</th>

									<th>
										<a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollableupdate">
											<button type="submit" class="btn btn-warning updatebtn" name="updatebtn" onclick="selectProduct('<?= $row['MMC_MATERIAL_CODE'] ?>','<?= $row['MMC_DESCRIPTION'] ?>','<?= $row['MMC_MATERIAL_SPEC'] ?>','<?= $row['MMC_BALANCE_QTY'] ?>','<?= $row['MMC_STOCK_CATEGORY'] ?>','<?= $row['MMC_BIN_LOCATION'] ?>','<?= $row['MMC_UNIT'] ?>','<?= $row['MMC_STATUS'] === 'A' ?>')">Update</button>

										</a>
									</th>

									<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"hidden/></th> -->
								</tr>
							<?php

								$index++;
							}
							?>
						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="pvcadd" />
					<a href="" data-bs-toggle="modal" data-bs-target="#pvcItemsAddModal">
						<button type="button" class="btn btn-primary">Add</button>
					</a>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <button type="submit" class="btn btn-success">
								Save changes </button> -->
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>
		</div>
	</div>

	</form>

	<!-- Add new, modal -->
	<div class="modal fade" id="pvcItemsAddModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title text-info" id="exampleModalScrollableTitle">PVC ITEMS UPDATE </h2>
					<img src="./static/img/Pvc.png" style="width: 80px;" alt="">

					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="veg2" method="POST">
						<table class="table table-hover">


							<form id="food" name="food" method="POST">
								<div class="form-group row">
									<label for="MaterialCode" class="col-sm-2 col-form-label">Material Code:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialCode" id="MaterialCode" placeholder="Material Code">
									</div>
								</div>
								<div class="form-group row">
									<label for="Description" class="col-sm-2 col-form-label">Description:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="Description" id="Description" placeholder="Description">
									</div>
								</div>
								<div class="form-group row">
									<label for="MaterialSpec" class="col-sm-2 col-form-label">Mat Spec:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="MaterialSpec" id="MaterialSpec" placeholder="Material Spec">
									</div>
								</div>
								<div class="form-group row">
									<label for="BalanceQty" class="col-sm-2 col-form-label">Balance Quantity:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="BalanceQty" id="BalanceQty" placeholder="Balance Qty">
									</div>
								</div>
								<div class="form-group row">
									<label for="StockCat" class="col-sm-2 col-form-label">Stock Category:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="StockCat" id="StockCat" placeholder="Stock Cat">
									</div>
								</div>
								<div class="form-group row">
									<label for="BinLocation" class="col-sm-2 col-form-label">Bin Location:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="BinLocation" id="BinLocation" placeholder="Bin Location ">
									</div>
								</div>
								<div class="form-group row">
									<label for="Unit" class="col-sm-2 col-form-label">Unit:</label>
									<div class="col-sm-10">
										<input type="text"  class="form-control-plaintext" name="Unit" id="Unit" value="NOS">
									</div>
								</div>


						</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="insert" value="vegitables" />
					<!-- <a href="" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                <button type="button" class="btn btn-primary" >Add</button>
								</a> -->

					<button type="submit" class="btn btn-secondary" onclick="modalclosefunction()">Close</button>
					<button type="submit" name="insertbtn" id="insertbtn" class="btn btn-success" onclick="modalfunction()">
						Save changes </button>
					<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
					<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
				</div>
			</div>

			</form>
		</div>
	</div>

	</form>
	<!-- end main  modal PVC ITEMS -->
	</div>
	</div>

</body>

<!-- Modal Spices -->
<script>
	$(document).ready(function() {
		$("#veg").submit(function(event) {
			var formData = $("#veg").serialize();
			//   var formData = {
			//     name: 'abc',
			//     email: 'abcd',
			//   };

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {
				console.log(data);
			});

			event.preventDefault();
		});
	});
</script>
<!-- Modal Spices -->
<script>
	$(document).ready(function() {
		$("#spices").submit(function(event) {
			var formData = $("#spices").serialize();
			//   var formData = {
			//     name: 'abc',
			//     email: 'abcd',
			//   };

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {
				console.log(data);
			});

			event.preventDefault();
		});
	});
</script>

<script>
	$(document).ready(function() {
		$("#spices").submit(function(event) {
			var formData = $("#spices").serialize();
			//   var formData = {
			//     name: 'abc',
			//     email: 'abcd',
			//   };

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {
				console.log(data);
			});

			event.preventDefault();
		});
	});
</script>

<script>
	$(document).ready(function() {
		$("#fish").submit(function(event) {
			var formData = $("#fish").serialize();
			//   var formData = {
			//     name: 'abc',
			//     email: 'abcd',
			//   };

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {
				console.log(data);
			});

			event.preventDefault();
		});
	});
</script>

<script>
	$(document).ready(function() {
		$("#dfish").submit(function(event) {
			var formData = $("#dfish").serialize();
			//   var formData = {
			//     name: 'abc',
			//     email: 'abcd',
			//   };

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {

			});

			event.preventDefault();
		});
	});
</script>

<script>
	$(document).ready(function() {
		$("#rice").submit(function(event) {
			var formData = $("#rice").serialize();
			//   var formData = {
			//     name: 'abc',
			//     email: 'abcd',
			//   };

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {
				console.log(data);
			});

			event.preventDefault();
		});
	});
</script>

<script>
	$(document).ready(function() {
		$("#pvcadd").submit(function(event) {
			var formData = $("#pvcadd").serialize();
			//   var formData = {
			//     name: 'abc',
			//     email: 'abcd',
			//   };

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {

			});

			event.preventDefault();
		});
	});
</script>

<script>
	function modalfunction() {
		alert("Records Saved Successfully!!");
	}
</script>

<script>
	function modalclosefunction() {
		alert("Are You Sure!!");
	}
</script>

</html>