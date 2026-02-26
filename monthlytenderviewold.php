<?php
session_start();
?>
<?php
include 'config.php';
if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
	header('Location: admin.php');
	exit();
}

$entry = $_SESSION['entry'];

// Determine which button was clicked and set the mtd_type value accordingly
$mtd_type = 'PI'; // Default value for Monthly Tenders
if (isset($_GET['type'])) {
    switch ($_GET['type']) {       
        case 'pvc':
            $mtd_type = 'PI';
            $current_button = 'PVC Items';
            break;
        case 'medicine':
            $mtd_type = 'MI';
            $current_button = 'Medicine Items';
            break;
        case 'cables':
            $mtd_type = 'CB';
            $current_button = 'Cables';
            break;
        default:
            $mtd_type = 'PI';
            $current_button = 'PVC Items';
    }
} else {
    $current_button = 'PVC Items';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="shortcut icon" href="./static/img/2.svg" />

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

<?php

$supcodevalue;

//Tender Data
$tenders = [];


global $tsql;

// Modified query to use the dynamic mtd_type value


$tsql = "SELECT *, 
       CASE 
           WHEN mtd_status = 'A' THEN 'Active' 
           WHEN mtd_status = 'I' THEN 'Inactive'
           ELSE mtd_status 
       END AS mtd_status 
FROM mms_tender_details WHERE mtd_type='$mtd_type' 
ORDER BY mtd_bidclose_date DESC 
LIMIT 10;";


$stmt = mysqli_query($con, $tsql);
if ($stmt === false) {
	echo "Error in query";
	die(print_r(mysqli_error($con), true));
}
while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {
	$tender = $row;
	$data[$row['mtd_tender_no']] = $row;
	// Map the tender type codes to the actual category names in the database
    $category_map = [
        'PI' => 'PVC Items',
        'MI' => 'Medicine Items', 
        'CB' => 'Cables'
    ];
    
    $category_name = $category_map[$mtd_type] ?? 'PVC Items';
    
    // Correct query joining both tables and filtering by category
    $tsql = "SELECT mst.msd_tender_no, mst.msd_supplier_code, msd.msd_supplier_name 
             FROM mms_suptender_details mst
             INNER JOIN mms_suppliers_details msd ON mst.msd_supplier_code = msd.msd_supplier_code 
             WHERE mst.msd_tender_no = '" . $row['mtd_tender_no'] . "' 
             AND msd.msd_supply_category = '$category_name'";
    
    $stmt2 = mysqli_query($con, $tsql);
    if ($stmt2 === false) {
        echo "Error in query: " . mysqli_error($con);
        continue; // Skip to next tender instead of dying
    }

	$suppilers = [];
	while ($row1 = mysqli_fetch_array($stmt2, MYSQLI_ASSOC)) {
		$suppiler = $row1;

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT, mtt_remark, mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('V') 
			GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";

		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt3 === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$vegitables = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($vegitables, $item);
		}


		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('S') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$spices = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($spices, $item);
		}


		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('F') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$fish = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($fish, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('D') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$dryfish = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($dryfish, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('O') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$oil = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($oil, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('Y') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$dryitems = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($dryitems, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('C') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$coconut = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($coconut, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('E') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$eggs = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($eggs, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('R') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$rice = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($rice, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('H') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$chicken = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($chicken, $item);
		}

		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('M') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$wrapPapers = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($wrapPapers, $item);
		}
       //pvc
		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('P') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$pvc = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($pvc, $item);
		}
		//medicine
		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('I') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$medicine = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($medicine, $item);
		}

		//cables
		$tsql = "SELECT MMC_DESCRIPTION,MMC_UNIT,  mtt_remark,mtt_price FROM mms_tenderprice_transactions
			RIGHT JOIN mms_material_catalogue ON mms_material_catalogue.MMC_MATERIAL_CODE = mms_tenderprice_transactions.mtt_material_code 
			AND mms_tenderprice_transactions.mtt_supplier_code = '" . $row1['msd_supplier_code'] . "' 
			AND mms_tenderprice_transactions.mtt_tender_no = '" . $row['mtd_tender_no'] . "'
			WHERE MMC_CAT_CODE in ('B') GROUP BY mms_material_catalogue.MMC_MATERIAL_CODE ORDER BY MMC_DESCRIPTION ASC";
		$stmt3 = mysqli_query($con, $tsql);
		if ($stmt === false) {
			echo "Error in query";
			die(print_r(mysqli_error($con), true));
		}
		$cable = [];
		while ($item = mysqli_fetch_array($stmt3, MYSQLI_ASSOC)) {
			array_push($cable, $item);
		}

		$items['V'] = $vegitables;
		$items['S'] = $spices;
		$items['F'] = $fish;
		$items['D'] = $dryfish;
		$items['O'] = $oil;

		// new
		$items['Y'] = $dryitems;
		$items['C'] = $coconut;
		$items['E'] = $eggs;
		$items['R'] = $rice;
		$items['H'] = $chicken;
		$items['M'] = $wrapPapers;
		$items['P'] = $pvc;
		$items['I'] = $medicine;
		$items['B'] = $cable;
		$suppiler['items'] = $items;
		array_push($suppilers, $suppiler);
	}
	$tender['suppilers'] = $suppilers;
	array_push($tenders, $tender);
}
// die(" <br>".json_encode($tenders)."<br>");

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
					<li class="sidebar-item ">
						<a class="sidebar-link" href="allsuppliersview.php">
							<i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Pending Suppliers</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="allactivesuppliersview.php">
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Registered Suppliers</span>
						</a>
					</li>

					<li class="sidebar-header">
						Tender Managment
					</li>
					<li class="sidebar-item ">
						<a class="sidebar-link" href="tenderview.php">
							<i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Tenders</span>
						</a>                        
					</li>
                    <li class="sidebar-item active">
						 <a class="sidebar-link" href="monthlytenderview.php">
							<i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Monthly Tenders</span>
						</a>
					</li>
					<?php if ($entry != 'N') : ?>
						<li class="sidebar-header">
							Food Managment
						</li>
						<li class="sidebar-item">
							<a class="sidebar-link" href="Admin_components/addfood.php">
								<i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Add Food</span>
							</a>
						</li>
					<?php endif; ?>
				</ul>

				<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<div class="d-grid">
							<a href="adminlogout.php" class="btn btn-primary" onclick="logoutfunction()">Logout</a>

						</div>
					</div>
				</div>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>
				<a href="" style="color: blue; font-weight: bolder; text-decoration: none;">
					HELLO <?php echo $_SESSION['name'] ?>! WELCOME TO eSupplier-CDPLC ADMIN DASHBOARD!!!
				</a>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<img src="./static/img/avatars/avatar1.jpg" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark"><?php echo $_SESSION['name'] ?></span>
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<div class="dropdown-divider"> </div>
								<a href="logout.php" class="dropdown-item" onclick="logoutfunction()">Logout</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
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
							eSupplier View Monthly Tender
						</strong>
					</h1>
					
					<!-- New Filter Buttons -->
					<div class="mb-3">
						<a href="monthlytenderview.php?type=pvc" class="btn <?= ($current_button == 'PVC Items') ? 'btn-primary btn-active' : 'btn-outline-primary' ?>">PVC Items</a>
						<a href="monthlytenderview.php?type=medicine" class="btn <?= ($current_button == 'Medicine Items') ? 'btn-primary btn-active' : 'btn-outline-primary' ?>">Medicine Items</a>
						<a href="monthlytenderview.php?type=cables" class="btn <?= ($current_button == 'Cables') ? 'btn-primary btn-active' : 'btn-outline-primary' ?>">Cables</a>
					</div>

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
			<?php include './components/footer.php' ?>
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