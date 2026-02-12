<?php
session_start();
date_default_timezone_set('Asia/Colombo');
if (!isset($_SESSION['sup_code'])) {
	header('Location: index.php');
}
if (!isset($_SESSION['sup_status']) || $_SESSION['sup_status'] === "A") {
	header('Location: profile.php');
}

include 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$success = true;
	if (isset($_POST['insert'])) {

		if ($_POST['insert'] == 'vegitables' || 'spices' || 'fish' || 'dfish' || 'rice') {

			$rowCount = count($_POST['MMC_DESCRIPTION']);

			for ($x = 0; $x <= ($rowCount - 1); $x++) {

				$MMC_DESCRIPTION = $_POST['MMC_DESCRIPTION'][$x];
				$MMC_UNIT = $_POST['MMC_UNIT'][$x];
				$MMC_REMARK = $_POST['MMC_REMARK'] && $_POST['MMC_REMARK'][$x] ? $_POST['MMC_REMARK'][$x] : null;
				$MMC_PRICE = $_POST['MMC_PRICE'] && $_POST['MMC_PRICE'][$x] ? $_POST['MMC_PRICE'][$x] : null;
				$MMC_MATERIAL_CODE = $_POST['MMC_MATERIAL_CODE'][$x];
				$MMC_CAT_CODE = $_POST['MMC_CAT_CODE'][$x];

				$suppliercode = isset($_SESSION['sup_code']) ? $_SESSION['sup_code'] : "";

				if (!$MMC_PRICE) {
					// If price is empty, delete the row
					$delete = "DELETE FROM mms_tenderprice_transactions 
							   WHERE mtt_supplier_code = '$suppliercode' 
							   AND mtt_material_code = '$MMC_MATERIAL_CODE' 
							   AND mtt_status = 'A' 
							   AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')";

					$stmt = mysqli_query($con, $delete);
					if ($stmt === false) {
						$success = false;
						echo "<script>alert('Error occurred while deleting the row!');</script>";
					} else {
						echo "<script>alert('Row deleted successfully!');</script>";
					}

					continue;
				}

				$sup_code = $_SESSION["sup_code"];
				$date_now = date('Y-m-d g:i A');

				if ($con === false) {
					print_r('error');
				}
				$select = "SELECT * FROM mms_tenderprice_transactions WHERE mtt_year = (SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A') AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A') AND mtt_supplier_code = '$suppliercode' AND mtt_material_code ='$MMC_MATERIAL_CODE' AND mtt_status = 'A'";

				$stmt = mysqli_query($con, $select);
				if ($stmt === false) {
					$success = false;
					continue;
				}
				$rows = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

				$insert = "";
				if ($rows !== null && $rows['mtt_price'] === $MMC_PRICE && $rows['mtt_remark'] === $MMC_REMARK) {
					continue;
				}

				if ($rows !== null) {
					$insert = "UPDATE mms_tenderprice_transactions SET mtt_remark='$MMC_REMARK',mtt_price='$MMC_PRICE',updated_by='$sup_code',updated_date='$date_now' 
					WHERE mtt_status = 'A' 
					AND mtt_supplier_code = '$suppliercode' 
					AND mtt_material_code ='$MMC_MATERIAL_CODE' 
					AND mtt_status = 'A' 
					AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')";
				} else {
					$insert = "INSERT INTO mms_tenderprice_transactions(mtt_year,mtt_tender_no,mtt_supplier_code,mtt_material_code,mtt_remark,mtt_price,mtt_status,created_by,created_date) VALUES ((SELECT mtd_year FROM mms_tender_details WHERE mtd_status = 'A'), (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A'), '$suppliercode', '$MMC_MATERIAL_CODE', '$MMC_REMARK', '$MMC_PRICE', 'A','$sup_code','$date_now')";
				}

				$stmt = mysqli_query($con, $insert);
				if ($stmt === false) {
					$success = false;
					echo "<script>alert('Error occurred while saving the data!');</script>";
				} else {
					echo "<script>alert('Data saved successfully!');</script>";
				}
			}

			mysqli_close($con);
			return;
		}
	}
}

?>

<?php
include './components/timecounter.php'
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
	<link rel="shortcut icon" href="./static/img/9.png" />

	<title>eSupplier-CDPLC</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<link href="./static/css/app.css" rel="stylesheet">
	<link href="./static/css/main.css" rel="stylesheet">

	<!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet"> -->

	<style>
		table th {
			position: sticky;
			top: 0;
			background-color: green;
		}

		.fade-scale {
			/* transform: scale(0); */
			/* opacity: 0; */
			-webkit-transition: all .25s linear;
			-o-transition: all .25s linear;
			transition: all .25s linear;
		}

		.fade-scale.in {
			opacity: 1;
			transform: scale(1);
		}

		.popup-message {
			display: none;
			/* position: fixed; */
			bottom: 20px;
			right: 20px;
			background-color: #fff;
			padding: 10px;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
			animation: popupAnimation 0.5s ease-out;
		}

		@keyframes popupAnimation {
			0% {
				opacity: 0;
				transform: translateY(20px);
			}

			100% {
				opacity: 1;
				transform: translateY(0);
			}
		}
	</style>

	<script src="./static/js/jquery-3.3.1.min.js"></script>

	<!-- <script src="./static/js/jquery.validate.min.js"></script>
	<script src="./static/js/jquery.validate.unobtrusive.min.js"></script> -->

	<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

	<!-- <script src="js/app.js"></script> -->


	<script>
		function myFunctionVeg() {
			alert("Data Saved Successfully!!!");
		}

		function donebtnfunction() {
			// alert("Tender Successfully added!");
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

	<script>
		$(document).ready(function() {

			var current_fs, next_fs, previous_fs; //fieldsets
			var opacity;
			var current = 1;
			var steps = $("fieldset").length;

			setProgressBar(current);

			$(".next").click(function() {

				current_fs = $(this).parent();
				next_fs = $(this).parent().next();

				//Add Class Active
				$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

				//show the next fieldset
				next_fs.show();
				//hide the current fieldset with style
				current_fs.animate({
					opacity: 0
				}, {
					step: function(now) {
						// for making fielset appear animation
						opacity = 1 - now;

						current_fs.css({
							'display': 'none',
							'position': 'relative'
						});
						next_fs.css({
							'opacity': opacity
						});
					},
					duration: 500
				});
				setProgressBar(++current);
			});

			$(".previous").click(function() {

				current_fs = $(this).parent();
				previous_fs = $(this).parent().prev();

				//Remove class active
				$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

				//show the previous fieldset
				previous_fs.show();

				//hide the current fieldset with style
				current_fs.animate({
					opacity: 0
				}, {
					step: function(now) {
						// for making fielset appear animation
						opacity = 1 - now;

						current_fs.css({
							'display': 'none',
							'position': 'relative'
						});
						previous_fs.css({
							'opacity': opacity
						});
					},
					duration: 500
				});
				setProgressBar(--current);
			});

			function setProgressBar(curStep) {
				var percent = parseFloat(100 / steps) * curStep;
				percent = percent.toFixed();
				$(".progress-bar")
					.css("width", percent + "%")
			}

			$(".submit").click(function() {
				return false;
			})
		});
	</script>

	<script>
		$(document).ready(function() {
			$('#btnDoneFunc').click(function(e) {
				e.preventDefault();
				$.ajax({
					type: "post",
					url: "SuppplierDone.php",
					data: $('#btnDoneFunc').serialize(),
					dataType: "text",
					success: function(response) {
						$('#messagedisplay').text(response);
					}
				})
			})
		});
	</script>

</head>

<!-- <body> -->


<body>
	<!-- Modal for preview list -->
	<div class="modal fade-scale" id="previewitemlist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered ">
			<div class="modal-content">
				<div class="modal-header">

					<h3 class="modal-title fw-bold text-center" id="exampleModalLabel">Preview Saved Items </h3>
					<br> &nbsp;&nbsp;&nbsp;
					<h4 class="popup-message" class="text-center" id="submitTenderMessage" style="display: none; text-align: center; color: red; font-weight: 500; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Please add prices to submit the tender</h4>

					<!-- <a href="prints/printAll.php?supid=<?php echo $_SESSION['sup_code']; ?>&tno=<?php echo $tNumber ?> " target="_blank">
					<button type="button" style="margin-left: 10px;" class="btn btn-success btn-lg" data-bs-dismiss="modal">Print</button>
				</a> -->
					<br>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
						<!-- All list showing -->
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">All List</button>
						</li>

						<li class="nav-item" role="presentation">
							<button class="nav-link " id="pills-veg-tab" data-bs-toggle="pill" data-bs-target="#pills-veg" type="button" role="tab" aria-controls="pills-veg" aria-selected="true">Vegetables</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-spices-tab" data-bs-toggle="pill" data-bs-target="#pills-spices" type="button" role="tab" aria-controls="pills-spices" aria-selected="false">Spices</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-fish-tab" data-bs-toggle="pill" data-bs-target="#pills-fish" type="button" role="tab" aria-controls="pills-fish" aria-selected="false">Fish</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-dryfish" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Dry Fish</button>
						</li>
						<!-- <li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-coconutoil" type="button" role="tab" aria-controls="pills-coconutoil" aria-selected="false">Coconut Oil and Creamer</button>
						</li> -->
						<!--  -->
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-dryitems" type="button" role="tab" aria-controls="pills-dryitems" aria-selected="false">Dry Items</button>
						</li>
						<!-- <li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-coconut" type="button" role="tab" aria-controls="pills-coconut" aria-selected="false">Coconut</button>
						</li> -->
						<!-- <li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-eggs" type="button" role="tab" aria-controls="pills-eggs" aria-selected="false">Eggs</button>
						</li> -->
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-rice" type="button" role="tab" aria-controls="pills-rice" aria-selected="false">Rice</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-chicken" type="button" role="tab" aria-controls="pills-chicken" aria-selected="false">Meat</button>
						</li>
						<!-- <li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-wrapp" type="button" role="tab" aria-controls="pills-wrapp" aria-selected="false">Wrapping papers</button>
						</li> -->

						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-wrapp" type="button" role="tab" aria-controls="pills-wrapp" aria-selected="false">Miscellaneous Items</button>
						</li>

					</ul>


					<div class="tab-content" id="pills-tabContent">
						<!-- start all list -->
						<div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
							<form id="alltab" action="dashboard.php?stage=1" method="GET">
								<table class="table table-hover table-bordered border-primary" id="alltab">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Category Name</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>

									<tbody id="allitems">

									</tbody>

									<?php

									// $suppliercode = $_SESSION['sup_code'];

									// $tsql = "SELECT MMC_DESCRIPTION, mtt_price FROM mms_tenderprice_transactions
									// LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no
									// LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code
									// and mms_tender_details.mtd_status = 'A'
									// WHERE mms_tenderprice_transactions.mtt_supplier_code = '$suppliercode' and MMC_CAT_CODE in ('V')";

									// $stmt = mysqli_query($con, $tsql);
									// if ($stmt === false) {
									// 	echo "Error in query";
									// 	die(print_r(mysqli_error($con), true));
									// }
									// $index = 0;
									// while ($row = mysqli_fetch_array($stmt)) {
									?>

									<!-- <tr>
										<td class="col-8">
											<?php echo $row['MMC_DESCRIPTION']; ?>
										</td>
										<td class="col-4">
											<?php echo $row['mtt_price']; ?>
										</td>
									</tr> -->
									<?php

									// 	$index++;
									// }
									?>
								</table>
							</form>
						</div>
						<!-- end all list -->
						<div class="tab-pane fade" id="pills-veg" role="tabpanel" aria-labelledby="pills-veg-tab">
							<form id="vegtab" action="dashboard.php?stage=1" method="GET">
								<table class="table table-hover table-bordered border-primary" id="vegtab">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>

									<tbody id="vegitems">

									</tbody>

									<?php

									// $suppliercode = $_SESSION['sup_code'];

									// $tsql = "SELECT MMC_DESCRIPTION, mtt_price FROM mms_tenderprice_transactions
									// LEFT JOIN mms_tender_details ON mms_tender_details.mtd_tender_no = mms_tenderprice_transactions.mtt_tender_no
									// LEFT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code
									// and mms_tender_details.mtd_status = 'A'
									// WHERE mms_tenderprice_transactions.mtt_supplier_code = '$suppliercode' and MMC_CAT_CODE in ('V')";

									// $stmt = mysqli_query($con, $tsql);
									// if ($stmt === false) {
									// 	echo "Error in query";
									// 	die(print_r(mysqli_error($con), true));
									// }
									// $index = 0;
									// while ($row = mysqli_fetch_array($stmt)) {
									?>

									<!-- <tr>
										<td class="col-8">
											<?php echo $row['MMC_DESCRIPTION']; ?>
										</td>
										<td class="col-4">
											<?php echo $row['mtt_price']; ?>
										</td>
									</tr> -->
									<?php

									// 	$index++;
									// }
									?>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-spices" role="tabpanel" aria-labelledby="pills-spices-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="spicesitems">

									</tbody>

								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-fish" role="tabpanel" aria-labelledby="pills-fish-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>

									<tbody id="fishitems">

									</tbody>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-dryfish" role="tabpanel" aria-labelledby="pills-dryfish-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>

									<tbody id="dryfishitems">

									</tbody>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-coconutoil" role="tabpanel" aria-labelledby="pills-coconutoil">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="rocitems">

									</tbody>
								</table>
							</form>
						</div>
						<!-- new -->
						<div class="tab-pane fade" id="pills-dryitems" role="tabpanel" aria-labelledby="pills-dryitems-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="dryitems">

									</tbody>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-coconut" role="tabpanel" aria-labelledby="pills-coconut-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="coconutitems">

									</tbody>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-eggs" role="tabpanel" aria-labelledby="pills-eggs-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="eggsitems">

									</tbody>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-rice" role="tabpanel" aria-labelledby="pills-rice-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="riceitems">

									</tbody>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-chicken" role="tabpanel" aria-labelledby="pills-chicken-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="chickenitems">

									</tbody>
								</table>
							</form>
						</div>
						<div class="tab-pane fade" id="pills-wrapp" role="tabpanel" aria-labelledby="pills-wrapp-tab">
							<form id="" method="post">
								<table class="table table-hover table-bordered border-primary">
									<thead>
										<tr class="fixed">
											<th class="bg-info">
												<h3 class="fw-bold">Description</h3>
											</th>
											<th class="bg-info">
												<h3 class="fw-bold">Price (Rs.)</h3>
											</th>

										</tr>
									</thead>
									<tbody id="wrapitems">

									</tbody>
								</table>
							</form>
						</div>
					</div>

				</div>
				<div class="modal-footer">

					<button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal">Close</button>
					<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
					<!-- <input type="submit" name="" class="next action-button" data-bs-toggle="modal" value="Submit" id="" title="" data-bs-target="" /> -->
					<input type="button" name="" class="btn btn-success btn-lg" data-bs-toggle="modal" value="Submit a Tender" id="btndonemodal" onclick="document.getElementById('btnDoneFunc').click()" />
				</div>
			</div>
		</div>
	</div>
	<!-- end preview list -->

	<!-- main secions start -->
	<div class="wrapper">
		<?php include './components/sidenav.php' ?>
		<div class="main">
			<?php include './components/navbar.php' ?>
			<!-- dashboard content -->
			<main class="content">
				<div class="container-fluid p-0">
					<div class="container-fluid">

						<!-- time counter -->
						<div class="row justify-content-center">
							<h1 id="TimeCounter" class="text-center" style="color:red; font-weight:bold"></h1>
							<h2 class="text-center" style="color:red; font-weight:bold">The Bidding will close on <?php echo $bidclose_date ?></h2>
							<h2 class="text-success font-weight-bold text-center" id="tenderopentime">
								Tender Open from <?php echo $stardate; ?> to <?php echo $enddate; ?> | Tender No: <?php echo $tNumber; ?>
							</h2>
						</div>
						<!-- time counter end -->

						<div class="row justify-content-center">
							<!-- justify-content-center -->
							<div class="col-12 col-sm-10 col-md-10 col-lg-6 col-xl-12 p-0 mt-3 mb-2">
								<div class="card px-0 pb-0">
									<!-- <h1 id="heading" class="text-center pt-2" style="color:blue"><strong>Colombo Dockyard PLC</strong></h1> -->
									<center><img class="center pt-2" src="./static/img/cdl_logo.png" style="width: 15%" alt="centered image"></center>
									<h3 id="" class="text-center" style="color: blue;"><strong>Tender For the Supply of Foods - Cash Price</strong></h3>
									<!-- <h3 class="text-center ">Po. Box: 906, Port of Colombo, Colombo 15.</h3> -->
									<!--<h2 class="pt-4">වර්ගය තෝරා ඉදිරියට යන්න</h2>-->

									<form id="msform" method="post">

										<!-- progressbar -->
										<ul id="progressbar">
											<li class="active" id="account"><strong><a>Approve</a></strong></li>
											<li id="personal"><strong><a>Categories</a></strong></li>
											<!-- <li id="payment"><strong><a href="#">Price</a></strong></li> -->
											<li id="confirm"><strong><a>Completed</a></strong></li>
										</ul>
										<div class="progress">
											<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
										</div> <br>

										<!-- 1st Page -->
										<?php
										require('pages/approve.php');
										?>

										<!-- page 2 -->
										<?php
										require('pages/categories.php');
										?>

										<!-- page 3 -->
										<?php
										require('pages/completed.php');
										?>

									</form>
								</div>
							</div>
						</div>
					</div>
					</form>

				</div>

				<!-- MODAL FOR Previewing added items as a list -->

				<!-- Modal Veg -->
				<div class="modal fade" id="vegModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">

							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">VEGETABLE ITEMS</h2>
								<img src="./static/img/vegetable.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb1">
							</center>

							<div class="modal-body">
								<form id="veg" action="dashboard.php?stage=2" method="POST">

									<table class="table table-hover">
										<thead>
											<tr class="fixed">
												<th class="bg-success">
													<h3 class="fw-bold">Item Name</h3>
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Description</h3>
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Unit: KG</h3>
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Remarks</h3>
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Tender Price (Rs.)</h3>
												</th>

											</tr>
										</thead>


										<?php

										$suppliercode = $_SESSION['sup_code'];

										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE ,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE  
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('V') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										// $tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_CODE ,MMC_CAT_CODE,MMC_STATUS,mms_tenderprice_transactions.mtt_price AS MMC_PRICE
										// FROM mms_material_catalogue
										// LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE  AND mtt_supplier_code = '$suppliercode'
										// WHERE MMC_CAT_CODE in ('V')";


										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>

												<!-- <th>
												<input class="form-control" style="max-width:100%; text-align: right;" type="number" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" <?= $row['MMC_STATUS'] === 'I' ? 'disabled' : '' ?> 
											     value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</th> -->

												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

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
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt1()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
								<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Spices -->
				<div class="modal fade" id="spicesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">SPICES ITEMS&nbsp;&nbsp;</h2>
								<img src="./static/img/spice.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb2">
							</center>
							<div class="modal-body">
								<b>
									<h2 id="mealAddMsg" style="color: green;" class="text-success"></h2>
								</b>
								<form id="spices" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>

										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];


										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('S') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

											</tr>
										<?php




											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="spices" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt2()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" value="spices">Save changes</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Fish -->
				<div class="modal fade" id="fishModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">FISH ITEMS</h2>
								<img src="./static/img/fish.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb3">
							</center>
							<div class="modal-body">
								<form id="fish" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>
										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];

										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('F') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";


										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />


												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

												<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"/></th> -->
											</tr>
										<?php

											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="fish" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt3()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
								<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Dry Fish -->
				<div class="modal fade" id="dryfishModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">DRY FISH ITEMS</h2>
								<img src="./static/img/dried-fish.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb4">
							</center>
							<div class="modal-body">
								<form id="dfish" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>

										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];

										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('D') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";
										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

												<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"/></th> -->
											</tr>
										<?php

											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="dfish" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt4()">
									Save
								</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
								<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Coconut Oil & Coconut Creamer-->
				<div class="modal fade" id="cocoilcreamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">COCONUT OIL & COCONUT CREAMER</h2>
								<img src="./static/img/coconut-oil.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb5">
							</center>
							<div class="modal-body">
								<form id="coconut-oil" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>
										</tr>

										<?php
										$suppliercode = $_SESSION['sup_code'];

										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'	
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('O') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";
										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

												<!-- <th><input type="text"  name="test[]" value="<?= $index ?>"/></th> -->
											</tr>
										<?php

											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="rice" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt5()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" id="insert" value="vegitables">Save changes</button> -->
								<!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Update</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Dry Items -->
				<div class="modal fade" id="dryItemsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">DRY ITEMS&nbsp;&nbsp;</h2>
								<img src="./static/img/dried-item.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb6">
							</center>
							<div class="modal-body">
								<b>
									<h2 id="mealAddMsg" style="color: green;" class="text-success"></h2>
								</b>
								<form id="dry" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>

										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];


										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('Y') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

											</tr>
										<?php
											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="spices" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt6()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" value="spices">Save changes</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal COCONUT -->
				<div class="modal fade" id="coconutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">COCONUT&nbsp;&nbsp;</h2>
								<img src="./static/img/coconut.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb7">
							</center>
							<div class="modal-body">
								<b>
									<h2 id="mealAddMsg" style="color: green;" class="text-success"></h2>
								</b>
								<form id="coconut" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>
										</tr>

										<?php
										$suppliercode = $_SESSION['sup_code'];

										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('C') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />


												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

											</tr>
										<?php

											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="spices" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt7()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" value="spices">Save changes</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Eggs -->
				<div class="modal fade" id="eggsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">EGGS&nbsp;&nbsp;</h2>
								<img src="./static/img/eggs.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb8">
							</center>
							<div class="modal-body">
								<b>
									<h2 id="mealAddMsg" style="color: green;" class="text-success"></h2>
								</b>
								<form id="eggs" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>

										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];


										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('E') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

											</tr>
										<?php
											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="spices" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt8()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" value="spices">Save changes</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Rice -->
				<div class="modal fade" id="riceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">RICE&nbsp;&nbsp;</h2>
								<img src="./static/img/rice.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb9">
							</center>
							<div class="modal-body">
								<b>
									<h2 id="mealAddMsg" style="color: green;" class="text-success"></h2>
								</b>
								<form id="rice" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>

										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];


										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('R') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />


												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

											</tr>
										<?php




											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="spices" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt9()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" value="spices">Save changes</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Chicken -->
				<div class="modal fade" id="chickenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">Meat&nbsp;&nbsp;</h2>
								<img src="./static/img/chicken-leg.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb10">
							</center>
							<div class="modal-body">
								<b>
									<h2 id="mealAddMsg" style="color: green;" class="text-success"></h2>
								</b>
								<form id="chicken" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>

										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];


										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('H') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

											</tr>
										<?php
											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="spices" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt10()">
									Save</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" value="spices">Save changes</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>

				<!-- Modal Wrapping Papers -->
				<div class="modal fade" id="wrappingpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title text-info" id="exampleModalScrollableTitle">MISCELLANEOUS ITEMS&nbsp;&nbsp;</h2>
								<img src="./static/img/gift-wrapping.png" style="width: 80px;" alt="">

								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<br>
							<center>
								<h3 class="text-success" id="lb11">
							</center>
							<div class="modal-body">
								<b>
									<h2 id="mealAddMsg" style="color: green;" class="text-success"></h2>
								</b>
								<form id="wrapping" method="POST" action="dashboard.php?stage=2">
									<table class="table table-hover">
										<tr class="fixed">
											<th class="bg-success">
												<h3 class="fw-bold">Item Name</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Description</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Unit: KG</h3 class="fw-bold">
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Remarks</h3>
											</th>
											<th class="bg-success">
												<h3 class="fw-bold">Tender Price (Rs.)</h3 class="fw-bold">
											</th>

										</tr>

										<?php

										$suppliercode = $_SESSION['sup_code'];


										$tsql = "SELECT MMC_DESCRIPTION, MMC_UNIT, MMC_MATERIAL_SPEC, MMC_MATERIAL_CODE ,MMC_CAT_CODE,mms_tenderprice_transactions.mtt_price AS MMC_PRICE,mms_tenderprice_transactions.mtt_remark AS MMC_REMARK
										FROM mms_material_catalogue
										LEFT JOIN mms_tenderprice_transactions ON mms_tenderprice_transactions.mtt_material_code = mms_material_catalogue.MMC_MATERIAL_CODE 
										AND mtt_supplier_code = '$suppliercode'
										AND mtt_tender_no = (SELECT mtd_tender_no FROM mms_tender_details WHERE mtd_status = 'A')
										WHERE MMC_CAT_CODE in ('M') AND MMC_STATUS = 'A' ORDER BY MMC_DESCRIPTION ASC";

										$stmt = mysqli_query($con, $tsql);
										if ($stmt === false) {
											echo "Error in query";
											die(print_r(mysqli_error($con), true));
										}
										$index = 0;
										while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
										?>
											<tr>
												<td><?php echo $row['MMC_DESCRIPTION']; ?> <input type="text" hidden name="MMC_DESCRIPTION[<?= $index ?>]" value="<?php echo $row['MMC_DESCRIPTION']; ?>"></td>
												<td><?php echo $row['MMC_MATERIAL_SPEC']; ?><input type="text" hidden name="MMC_MATERIAL_SPEC[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_SPEC']; ?>"></td>
												<td><?php echo $row['MMC_UNIT']; ?><input type="text" hidden name="MMC_UNIT[<?= $index ?>]" value="<?php echo $row['MMC_UNIT']; ?>"></td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="text" name="MMC_REMARK[<?= $index ?>]" id="MMC_REMARK[<?= $index ?>]" value="<?= $row['MMC_REMARK']; ?>" placeholder="Remark" />
												</td>
												<td>
													<input class="form-control" style="max-width:100%; text-align: right;" type="number" step="0.01" name="MMC_PRICE[<?= $index ?>]" id="MMC_PRICE[<?= $index ?>]" value="<?= $row['MMC_PRICE']; ?>" placeholder="Price" />
												</td>
												<td style="display:none"><?php echo $row['MMC_MATERIAL_CODE']; ?> <input type="text" hidden name="MMC_MATERIAL_CODE[<?= $index ?>]" value="<?php echo $row['MMC_MATERIAL_CODE']; ?>"></td>
												<td style="display:none"><?php echo $row['MMC_CAT_CODE']; ?> <input type="text" hidden name="MMC_CAT_CODE[<?= $index ?>]" value="<?php echo $row['MMC_CAT_CODE']; ?>"></td>

											</tr>
										<?php
											$index++;
										}
										?>
									</table>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="insert" value="spices" />
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" onclick="changeTxt11()">
									Save
								</button>
								<!-- <button type="submit" class="btn btn-primary" name="insert" value="spices">Save changes</button> -->
							</div>
						</div>
					</div>
				</div>
				</form>
			</main>

			<!-- footer start -->
			<?php include './components/footer.php' ?>
		</div>
	</div>

</body>

<!-- messages -->
<script>
	function changeTxt1() {
		//  document.getElementById('lb1').innerHTML = 'Data Saved Succesfully';
		//  alert("Data Saved Succesfully");
		var element = document.getElementById("lb1");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt2() {

		var element = document.getElementById("lb2");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt3() {

		var element = document.getElementById("lb3");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt4() {

		var element = document.getElementById("lb4");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt5() {

		var element = document.getElementById("lb5");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt6() {

		var element = document.getElementById("lb6");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt7() {

		var element = document.getElementById("lb7");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt8() {

		var element = document.getElementById("lb8");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt9() {

		var element = document.getElementById("lb9");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt10() {

		var element = document.getElementById("lb10");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}

	function changeTxt11() {

		var element = document.getElementById("lb11");
		element.innerHTML = "Data Saved Succesfully";
		setTimeout(clearValue, 1500);

		function clearValue() {
			element.innerHTML = "";
		}
	}
</script>


<!-- modal veg -->
<script>
	$(document).ready(function() {
		$("#veg").submit(function(event) {
			var formData = $("#veg").serialize();

			$.ajax({
				type: "POST",
				url: "dashboard.php?stage=2",
				data: formData,
				dataType: "json",
				encode: true,
			}).done(function(data) {
				console.log(data);
				if (data.status === "success") {
					document.getElementById('lb').innerHTML = 'Data Saved Succesfully';
					$('#vegtable').load(location.href + " #vegtable");
				} else {
					document.getElementById('lb').innerHTML = 'Data Saved Failed';
				}


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

<!-- modal spices -->
<script>
	$(document).ready(function() {
		$("#spices").submit(function(event) {
			var formData = $("#spices").serialize();

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

<!-- modal fish -->
<script>
	$(document).ready(function() {
		$("#fish").submit(function(event) {
			var formData = $("#fish").serialize();

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

<!-- dry fish -->
<script>
	$(document).ready(function() {
		$("#dfish").submit(function(event) {
			var formData = $("#dfish").serialize();
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

<!-- coconut-oil.png -->
<script>
	$(document).ready(function() {
		$("#coconut-oil").submit(function(event) {
			var formData = $("#coconut-oil").serialize();

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

<!-- dry items -->
<script>
	$(document).ready(function() {
		$("#dry").submit(function(event) {
			var formData = $("#dry").serialize();

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

<!-- coconut -->
<script>
	$(document).ready(function() {
		$("#coconut").submit(function(event) {
			var formData = $("#coconut").serialize();

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

<!-- eggs -->
<script>
	$(document).ready(function() {
		$("#eggs").submit(function(event) {
			var formData = $("#eggs").serialize();

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

<!-- rice -->
<script>
	$(document).ready(function() {
		$("#rice").submit(function(event) {
			var formData = $("#rice").serialize();

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

<!-- chicken -->
<script>
	$(document).ready(function() {
		$("#chicken").submit(function(event) {
			var formData = $("#chicken").serialize();
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

<!-- wrapping -->
<script>
	$(document).ready(function() {
		$("#wrapping").submit(function(event) {
			var formData = $("#wrapping").serialize();
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
	function popupMessage() {
		$('#submitTenderMessage').fadeIn('slow').delay(1300).fadeOut('slow', popupMessage);
	};
</script>

<script>
	function loadInventory() {
		// console.log('Works !!!!');

		// all items
		// $.ajax({
		// 	type: 'get',
		// 	url: 'allitemsinventory.php',
		// 	// data: {
		// 	// 	type: 'vegies',
		// 	// },
		// 	success: function(response) {

		// 		if (response) {
		// 			let list = JSON.parse(response);
		// 			let rowData = ''
		// 			list.forEach(element => {
		// 				// rowData += `<tr><td>${element.CategoryName}</td><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
		// 				rowData += `<tr><td>${element.CategoryName}</td><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
		// 			});
		// 			console.log(rowData);
		// 			$('#allitems').html(rowData);
		// 		}
		// 	}
		// });

		$.ajax({
			type: 'get',
			url: 'allitemsinventory.php',
			// data: {
			//     type: 'vegies',
			// },
			success: function(response) {
				if (response) {
					let list = JSON.parse(response);
					let rowData = '';
					list.forEach(element => {
						rowData += `<tr><td>${element.CategoryName}</td><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`;
					});
					console.log(rowData);
					$('#allitems').html(rowData);

					// Check if the list is empty
					if (list.length === 0) {
						$('#btndonemodal').prop('disabled', true);
						$('#submitTenderMessage').show();
						// popupMessage();
					} else {
						$('#btndonemodal').prop('disabled', false);
						$('#submitTenderMessage').hide();
					}
				} else {
					// Handle the case when response is empty or not received
					$('#allitems').html('No items found.');
					$('#btndonemodal').prop('disabled', true);
					$('#submitTenderMessage').show();
					// popupMessage();
				}
			}
		});

		$.ajax({
			type: 'get',
			url: 'allitemsinventory.php',
			// data: {
			//     type: 'vegies',
			// },
			success: function(response) {
				if (response) {
					let list = JSON.parse(response);
					let rowData = '';
					list.forEach(element => {
						rowData += `<tr><td>${element.CategoryName}</td><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`;
					});
					console.log(rowData);
					$('#allitems').html(rowData);

					// Check if the list is empty
					if (list.length === 0) {
						$('#submitTenderButton').prop('disabled', true);
						$('#submitTenderMessage').show();
					} else {
						$('#submitTenderButton').prop('disabled', false);
						$('#submitTenderMessage').hide();
					}
				} else {
					// Handle the case when response is empty or not received
					$('#allitems').html('No items found.');
					$('#submitTenderButton').prop('disabled', true);
					$('#submitTenderMessage').show();
				}
			}
		});


		$('#submitTenderButton').on('click', function() {
			if ($(this).prop('disabled')) {
				alert('Please add your prices before submitting the tender.');
			}
		});


		// VEG
		$.ajax({
			type: 'get',
			url: 'veginventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#vegitems').html(rowData);
				}
			}
		});

		// spices items
		$.ajax({
			type: 'get',
			url: 'spicesinventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#spicesitems').html(rowData);
				}
			}
		});

		// fish items
		$.ajax({
			type: 'get',
			url: 'fishinventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#fishitems').html(rowData);
				}
			}
		});

		// DRY FISH
		$.ajax({
			type: 'get',
			url: 'dryfishinventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#dryfishitems').html(rowData);
				}
			}
		});

		// COCONUT OIL AND CEAMER items
		$.ajax({
			type: 'get',
			url: 'rocinventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#rocitems').html(rowData);
				}
			}
		});

		// DRY ITEMS
		$.ajax({
			type: 'get',
			url: 'dryItemsInventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#dryitems').html(rowData);
				}
			}
		});

		// coconut
		$.ajax({
			type: 'get',
			url: 'coconutInventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#coconutitems').html(rowData);
				}
			}
		});

		// eggs
		$.ajax({
			type: 'get',
			url: 'eggsInventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#eggsitems').html(rowData);
				}
			}
		});

		// rice
		$.ajax({
			type: 'get',
			url: 'riceInventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#riceitems').html(rowData);
				}
			}
		});

		// chicken
		$.ajax({
			type: 'get',
			url: 'chickenInventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#chickenitems').html(rowData);
				}
			}
		});

		// wrap items
		$.ajax({
			type: 'get',
			url: 'wrapInventory.php',
			// data: {
			// 	type: 'vegies',
			// },
			success: function(response) {

				if (response) {
					let list = JSON.parse(response);
					let rowData = ''
					list.forEach(element => {
						rowData += `<tr><td>${element.MMC_DESCRIPTION}</td><td>${element.mtt_price}</td></tr>`
					});
					console.log(rowData);
					$('#wrapitems').html(rowData);
				}
			}
		});
	}

	// button done function
	function btnDoneFunc() {
		$.ajax({
			type: 'post',
			url: 'SuppplierDone.php',
			data: {
				name: name,
			},
			success: function(response) {
				$('#res').html(response);
			}
		});

	}
</script>

<script>
	function validate() {
		var ex = /^[0-9]{5}$/;
		if (ex.test(document.getElementById('MMC_PRICE').value) == false) {
			// alert code goes here
		}
	}
</script>



<script src="./static/js/app.js"></script>
<!-- timer script sessionUnset -->
<script src="js/sessionUnset.js"></script>
<script src="js/translate.js"></script>



</html>