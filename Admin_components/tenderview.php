<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo '<!-- DEBUG: tenderview.php loaded -->';
?>
<?php
session_start();
include '../config.php';
if (!isset($_SESSION['mobile_number']) || !isset($_SESSION['name']) || !isset($_SESSION['entry'])) {
	header('Location: ../admin.php');
	exit();
}
$entry = $_SESSION['entry'];
require_once __DIR__ . '/../backend/queries/tender_queries.php';
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
	</style>

</head>

<?php
$supcodevalue;
// Debug: Check DB connection
if (!$con) {
	die('<div class="alert alert-danger">Database connection failed.</div>');
}
$tenders = getTenders($con, null);
if ($tenders === false) {
	echo '<div class="alert alert-danger">Query failed: ' . mysqli_error($con) . '</div>';
}
if (empty($tenders)) {
	echo '<div class="alert alert-warning text-center">No tenders found.</div>';
}
// ...existing tender rendering code from original tenderview.php...
?>