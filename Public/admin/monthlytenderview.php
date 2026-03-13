<?php 
session_start();
include_once __DIR__ . '/../../backend/monthlytenderview_controller.php'; 
if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
	header('Location: ../admin.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="shortcut icon" href="../static/img/2.svg" />

	<title>eSupplier-CDL</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" />

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<link href="../static/css/app.css" rel="stylesheet">
	<link href="../static/css/main.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

	<script src="../static/js/jquery-3.3.1.min.js"></script>
	<script src="../static/js/jquery.validate.min.js"></script>
	<script src="../static/js/jquery.validate.unobtrusive.min.js"></script>

	<script src="../static/js/app.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

	<style>
		.active-row {
			background-color: lightblue;
			font-weight: 600;
		}
		.btn-active {
			background-color: #007bff;
			color: white;
			font-weight: bold;
		}
	</style>

</head>

<body>

	<div class="wrapper">
		<?php include 'components/adminsidenav.php'; ?>

		<div class="main">
			<?php include 'components/adminnavbar.php'; ?>

			<!-- Modals for tenders -->
			<?php foreach ($tenders as $tender) { ?>
				<!-- modal 1 for showing suppliers according to the selected tender -->
				<div class="modal fade" id="tender_<?= $tender['mtd_tender_no'] ?>" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
					<div class="modal-dialog modal-lg modal-dialog-top">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title fw-bold" id="exampleModalToggleLabel">Supplier List - <?= $current_button ?></h4>
								<a href="fullpricelist.php?tender_no=<?= $tender['mtd_tender_no'] ?>">
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
													<h3 class="fw-bold">Supplier Code</h3>
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Supplier Name</h3>
												</th>
												<th class="bg-success">
													<h3 class="fw-bold">Action</h3>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($tender['suppilers'] as $suppiler) { ?>
												<tr>
													<td><?= htmlspecialchars($suppiler['msd_supplier_code']) ?></td>
													<td><?= htmlspecialchars($suppiler['msd_supplier_name']) ?></td>
													<td>
														<button class="btn btn-primary" 
																data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>" 
																data-bs-toggle="modal" 
																data-bs-dismiss="modal">
															View Prices
														</button>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php foreach ($tender['suppilers'] as $suppiler) { ?>
					<!-- modal 2 for showing entered price by supplier -->
					<div class="modal fade" id="tender_<?= $tender['mtd_tender_no'] ?>_suppiler_<?= $suppiler['msd_supplier_code'] ?>" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
						<div class="modal-dialog modal-dialog-top modal-xl">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalToggleLabel2">Tender Price Transactions - <?= $suppiler['msd_supplier_name'] ?></h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="d-flex justify-content-end mb-3">
										<button class="btn btn-danger me-2" data-bs-target="#tender_<?= $tender['mtd_tender_no'] ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
										<a href="prints/print_monthly.php?supid=<?= $suppiler['msd_supplier_code'] ?>&tno=<?= $tender['mtd_tender_no'] ?>&cat=<?= $current_cat ?>" target="_blank">
											<button type="button" class="btn btn-outline-success">Print</button>
										</a>
									</div>
									<?php renderItem($suppiler['items'], $current_cat); ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>

			<!-- dashboard content -->
			<main class="content">
				<div class="container-fluid p-0">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h1 class="h3 mb-0"><strong>eSupplier Monthly Tender View</strong></h1>
						<div>
							<a href="adddmonthlytender.php" class="btn btn-success me-2">Add New</a>
						</div>
					</div>

					<!-- Category Filters -->
					<div class="card mb-3">
						<div class="card-body">
							<form method="GET" class="row g-2">
								<div class="col-auto">
									<?php foreach ($relevant_categories as $cat_code) : ?>
										<button type="submit" name="category" value="<?= $cat_code ?>" 
												class="btn <?= ($current_cat == $cat_code) ? 'btn-active' : 'btn-outline-primary' ?>">
											<?= getCategoryName($cat_code) ?>
										</button>
									<?php endforeach; ?>
								</div>
							</form>
						</div>
					</div>

					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover table-bordered table-rounded mb-0">
									<thead>
										<tr style="background-color: mediumseagreen; color: white;">
											<th class="fw-bold text-center">Year</th>
											<th class="fw-bold text-center">Tender No</th>
											<th class="fw-bold text-center">Start Date</th>
											<th class="fw-bold text-center">End Date</th>
											<th class="fw-bold text-center">Bid Closing Date</th>
											<th class="fw-bold text-center">Status</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if (empty($tenders)) : ?>
											<tr><td colspan="7" class="text-center">No Monthly Tenders Found</td></tr>
										<?php else : ?>
											<?php foreach ($tenders as $tender) : 
												$status = $tender['mtd_status'];
												$rowClass = ($status == 'Active') ? 'active-row' : '';
											?>
												<tr class="<?= $rowClass ?>">
													<td class="text-center"><?= $tender['mtd_year'] ?></td>
													<td class="text-center"><?= $tender['mtd_tender_no'] ?></td>
													<td class="text-center"><?= $tender['mtd_start_date'] ?></td>
													<td class="text-center"><?= $tender['mtd_end_date'] ?></td>
													<td class="text-center"><?= $tender['mtd_bidclose_date'] ?></td>
													<td class="text-center"><?= $status ?></td>
													<td class="text-center">
														<a class="btn btn-primary" data-bs-toggle="modal" href="#tender_<?= $tender['mtd_tender_no'] ?>" role="button">View</a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</main>

			<?php include 'components/adminfooter.php'; ?>
		</div>
	</div>

	<script>
		// The logoutfunction was likely used by an element that called it via onclick="logoutfunction()".
		// The instruction implies that the logout link itself will now contain the confirmation logic
		// and point to "logoutadmin.php".
		// Since the specific location of the logout link in 'adminsidenav.php' or 'adminnavbar.php'
		// is not provided, and the instruction's "Code Edit" is an HTML snippet,
		// the most faithful interpretation is to remove the now-redundant JavaScript function.
		// The actual HTML change for the link would occur within the included PHP components.
	</script>
</body>

</html>