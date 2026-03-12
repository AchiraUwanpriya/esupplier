<?php include_once __DIR__ . '/../../backend/tenderview_controller.php'; ?>

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

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

	<style>
		.active-row {
			background-color: lightblue;
			font-weight: 600;
		}
	</style>

</head>

<?php

function renderItem($items, $cat)
{
	if ($items && $items[$cat]) {
?>
		<table id="<?= $cat ?>" class="table table-hover table-bordered border-primary">
			<thead>
				<tr class="fixed">
					<th class="bg-info">
						<h3 class="fw-bold text-center">Description</h3>
					</th>
					<th class="bg-info">
						<h3 class="fw-bold text-center">Unit</h3>
					</th>
					<th class="bg-info">
						<h3 class="fw-bold text-center">Remarks</h3>
					</th>
					<th class="bg-info">
						<h3 class="fw-bold text-center">Price (Rs.)</h3>
					</th>
				</tr>
			</thead>
			<?php
			foreach ($items[$cat] as $obj) {
			?>
				<tr>
					<td class="col-4">
						<?= $obj['MMC_DESCRIPTION'] ?>
					</td>
					<td class="col-2 text-center">
						<?= $obj['MMC_UNIT'] ?>
					</td>
					<td class="col-3 text-center">
						<?= $obj['mtt_remark'] ?>
					</td>
					<td class="col-3 text-center">
						<?= $obj['mtt_price'] ?>
					</td>
				</tr>
			<?php
			}
			?>
		</table>
<?php
	} else {
		echo "No Availabale Data";
	}
}
?>

<body>

	<div class="wrapper">
		<?php include 'components/adminsidenav.php'; ?>

		<div class="main">
			<?php include 'components/adminnavbar.php'; ?>

			<?php
			foreach ($tenders as $tender) {
			?>
				<!-- modal 1 for showing suppliers according to the selected tender -->
				<div class="modal fade" id="tender_<?= $tender['mtd_tender_no'] ?>" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
					<div class="modal-dialog modal-lg modal-dialog-top">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title fw-bold" id="exampleModalToggleLabel">Supplier List</h4>
								<a href="fullpricelist.php?tender_no=<?= $tender['mtd_tender_no'] ?>">
									<!-- <a href="fullpricelist_update.php"> -->
									<button type="submit" class="btn btn-success" style="margin-left: 500px">Full Price Schedule</button>
								</a>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div style="height:150;overflow:auto;">
									<table class="table table-hover">
										<thead>
											<tr class="fixed">
												<th class="bg-success">
													<h3 class="fw-bold">Supplier Code</h3 class="fw-bold">
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Supplier Name</h3 class="fw-bold">
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Action</h3 class="fw-bold">
												</th>
											</tr>
										</thead>

										<?php
										foreach ($tender['suppilers'] as $suppiler) {
										?>
											<tbody>
												<tr>
													<td><?= $suppiler['msd_supplier_code'] ?></td>
													<td><?= $suppiler['msd_supplier_name'] ?></td>
													<td><button class="btn btn-primary" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">View Prices</button></td>
												</tr>
											</tbody>

										<?php
										}
										?>
									</table>
								</div>
							</div>
							<div class="modal-footer">

							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>

			<?php


			foreach ($tenders as $tender) {
				foreach ($tender['suppilers'] as $suppiler) {
			?>
					<!-- modal 2 for showing entered price by supplier -->
					<div class="modal fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">

						<div class="modal-dialog modal-dialog-top  modal-xl">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalToggleLabel2">Tender Price Transactions</h5><br>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<?php

								// get supplier code
								$supcodevalue = $suppiler['msd_supplier_code'];
								global $supcodevalue;

								// get tender no
								$tendernovalue = $tender['mtd_tender_no'];
								global $tendernovalue;

								?>
								<!-- Previewing prices -->
								<div class="modal-body">
									<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
										<li class="nav-item" role="presentation">
											<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_V" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Vegetables</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_S" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Spices</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_F" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Fish</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_D" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Dry Fish</button>
										</li>
										<!-- <li class="nav-item" role="presentation"> -->
										<!-- <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_O" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Coconut oil and Creamer</button> -->
										<!-- </li> -->
										<!--  -->
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_Y" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Dry Items</button>
										</li>
										<!-- <li class="nav-item" role="presentation"> -->
										<!-- <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_C" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Coconut</button> -->
										<!-- </li> -->
										<!-- <li class="nav-item" role="presentation"> -->
										<!-- <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_E" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Eggs</button> -->
										<!-- </li> -->
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_R" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Rice</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_H" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Meat</button>
										</li>
										<!-- <li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_W" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Wrapping papers</button>
										</li> -->
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_M" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Miscellaneous Items</button>
										</li>
										 <!-- pvc -->
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_P" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">PVC Items</button>
										</li>
										<!-- medicine -->
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_I" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Medicine Items</button>
										</li>
										<!-- cables -->
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_B" type="button" role="tab" aria-controls="pills-dryfish" aria-selected="false">Cables</button>
										</li>
									</ul>
									<div class="tab-content" id="pills-tabContent">
										<div class="tab-pane fade show active" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_V" role="tabpanel" aria-labelledby="pills-home-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printveg.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?> " target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'V') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_S" role="tabpanel" aria-labelledby="pills-profile-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printspices.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'S') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_F" role="tabpanel" aria-labelledby="pills-contact-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printfish.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'F') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_D" role="tabpanel" aria-labelledby="pills-dryfish-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printdryfish.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'D') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_O" role="tabpanel" aria-labelledby="pills-riceoil-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printroc.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'O') ?>
										</div>
										<!-- new categories -->
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_Y" role="tabpanel" aria-labelledby="pills-riceoil-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printdryitems.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'Y') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_C" role="tabpanel" aria-labelledby="pills-riceoil-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printcoconut.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'C') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_E" role="tabpanel" aria-labelledby="pills-riceoil-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printeggs.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'E') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_R" role="tabpanel" aria-labelledby="pills-riceoil-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printrice.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'R') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_H" role="tabpanel" aria-labelledby="pills-riceoil-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printchicken.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'H') ?>
										</div>
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_M" role="tabpanel" aria-labelledby="pills-riceoil-tab">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printmitems.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'M') ?>
										</div>
										<!-- pvc -->
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_P" role="tabpanel" aria-labelledby="">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printmitems.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'P') ?>
										</div>
										<!-- medicine -->
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_I" role="tabpanel" aria-labelledby="">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printmitems.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'I') ?>
										</div>
										<!-- cables -->
										<div class="tab-pane fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>_B" role="tabpanel" aria-labelledby="">
											<button style="float: right; margin-left: 10px;" class="btn btn-danger mb-2" href="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
											<a href="prints/printmitems.php?supid=<?php echo $supcodevalue ?>&tno=<?php echo $tendernovalue ?>" target="_blank">
												<button style="float: right;" type="button" class="btn btn-outline-success mb-2">Print</button>
											</a>
											<?php renderItem($suppiler['items'], 'B') ?>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<!-- <button type="button" class="btn btn-outline-success">Print</button>
										<button class="btn btn-danger" data-bs-target="#firstmodal" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button> -->
								</div>
							</div>
						</div>
					</div>
			<?php
				}
			}
			?>

			<!-- dashboard content -->
			<main class="content">
				<div class="container-fluid p-0">
					<h1 class="h3 mb-3">
						<strong>
							eSupplier View Tender
						</strong>
					</h1>
					<a href="addtender.php">
						<button type="submit" class="btn btn-success">Add New </button>
					</a>

					<div style="height: 100%">
						<div class="content">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-hover table-bordered table-rounded">
											<thead>
												<tr style="background-color: mediumseagreen; color: white;">
													<th class="fw-bold text-center" scope="col">Year</th>
													<th class="fw-bold text-center" scope="col">Tender No</th>
													<th class="fw-bold text-center" scope="col">Start Date</th>
													<th class="fw-bold text-center" scope="col">End Date</th>
													<th class="fw-bold text-center" scope="col">Bid Closing Date</th>
													<th class="fw-bold text-center" scope="col">Status</th>
													<th scope="col" class="text-center">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$entry = $_SESSION['entry'];

												//foreach ($tenders as $tender) {
												for ($i = 0; $i < count($tenders); $i++) {
													$tender = $tenders[$i];
													$mtd_bidclose_date = $tender['mtd_bidclose_date'];
													date_default_timezone_set('Asia/Colombo');
													$currentDateTime = new DateTime();

													$tn_status = $tender['mtd_status'];

													if ($tn_status == 'Active') {
														$rowClass = 'active-row';
													}

													$bidCloseDateTime = DateTime::createFromFormat('Y-m-d g:i A', $mtd_bidclose_date);

													if ($tn_status == 'Active' && $bidCloseDateTime >= $currentDateTime) {
														$rowClass = 'active-row';
														$viewButtonDisabled = true;
													} elseif ($tn_status == 'Active' && $bidCloseDateTime <= $currentDateTime) {
														$rowClass = 'active-row';
														$viewButtonDisabled = false;
													} else {
														$rowClass = '';
														$viewButtonDisabled = false;
													}
												?>
													<!-- <tr class="<?php echo $rowClass; ?>" style="<?= $i == 0 ? 'background-color:red;' : '' ?>"> -->
													<tr class="<?php echo $rowClass; ?>" style="<?= $i == 0 ? 'background-color: #00FFFF;' : '' ?>">
														<td class="text-center"><?php echo $tender['mtd_year']; ?></td>
														<td class="text-center"><?php echo $tender['mtd_tender_no']; ?></td>
														<td class="text-center"><?php echo $tender['mtd_start_date']; ?></td>
														<td class="text-center"><?php echo $tender['mtd_end_date']; ?></td>
														<td class="text-center"><?php echo $tender['mtd_bidclose_date']; ?></td>
														<td class="text-center"><?php echo $tender['mtd_status']; ?></td>
														<td class="text-center">
															<a class="btn btn-primary <?php if ($viewButtonDisabled) echo 'disabled'; ?>" data-bs-toggle="modal" href="#tender_<?= $tender['mtd_tender_no'] ?>" role="button">View</a>
														</td>
													</tr>
												<?php
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
			<?php include '../../components/footer.php' ?>
		</div>
</body>

<script>
	function logoutfunction() {
		alert("Please Confirm To Logout!!");
	}

	function myFunctionVeg() {
		alert("Data Saved Successfully!!!");
	}
</script>

</html>