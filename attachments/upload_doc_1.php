<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
	return header('Location: ../profile.php');
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
	$suppliercode = $_SESSION['sup_code'];
	// $_SESSION['sup_code'] = $suppliercode;

	$createdby = $_SESSION['sup_code'];
	$createddate = date('Y-m-d');

	if (isset($_POST['submit']) && isset($_FILES['my_image1'])) {
		include "../config.php";


		$img_name = $_FILES['my_image1']['name'];
		$img_size = $_FILES['my_image1']['size'];
		$tmp_name = $_FILES['my_image1']['tmp_name'];
		$error = $_FILES['my_image1']['error'];

		if ($error === 0) {

			$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
			$img_ex_lc = strtolower($img_ex);

			$allowed_exs = array("jpg", "jpeg", "png", "pdf");

			if (!in_array($img_ex_lc, $allowed_exs)) {
				$em = "You can't upload files of this type";
				return header("Location: ../profile.php?error1=$em");
			}

			if ($img_size > 20000000) {
				$em = "Sorry, your file is too large.";
				return header("Location: ../profile.php?error1=$em");
			}

			$new_img_name = uniqid("BR-", true) . '.' . $img_ex_lc;
			$img_upload_path = '../uploads/bussiness_register/' . $new_img_name;
			move_uploaded_file($tmp_name, $img_upload_path);

			// Insert into Database
			$sql = "INSERT into mms_supplier_attachments (msd_sup_code,msd_file_name,msd_file_path,msd_status,created_by,created_date,updated_by,updated_date) VALUES
			('$suppliercode','$new_img_name','$img_upload_path','A','$createdby','$createddate','NULL','NULL')";
			mysqli_query($con, $sql);

			echo '<script type="text/javascript">alert("Records Saved Successfully!!");
			setTimeout(function() { window.location.href = "../profile.php"; });
			</script>';
		} else {
			$em = "Unknown error occurred!";
			return header("Location: ../profile.php?error1=$em");
		}
	}
	//die("submit".isset($_POST['submit'])." my_image1". isset($_FILES['my_image1']));
	// return header("Location: profile.php");
}

?>

<!-- $em = "Records Saved Successfully!!";
			return header("Location: profile.php?error1=$em"); -->