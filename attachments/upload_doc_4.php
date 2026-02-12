
<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
	return header('Location: profile.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$suppliercode = $_SESSION['sup_code'];
	$_SESSION['sup_code'] = $suppliercode;



	if (isset($_POST['submit']) && isset($_FILES['my_image'])) {
		include "config.php";

		// echo "<pre>";
		// print_r($_FILES['my_image']);
		// echo "</pre>";

		$img_name = $_FILES['my_image']['name'];
		$img_size = $_FILES['my_image']['size'];
		$tmp_name = $_FILES['my_image']['tmp_name'];
		$error = $_FILES['my_image']['error'];

		if ($error === 0) {
			if ($img_size > 1250000) {
				$em = "Sorry, your file is too large.";
				return header("Location: dashboard.php?error=$em");
			} else {
				$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
				$img_ex_lc = strtolower($img_ex);

				$allowed_exs = array("jpg", "jpeg", "png");

				if (in_array($img_ex_lc, $allowed_exs)) {
					$new_img_name = uniqid("form20-", true) . '.' . $img_ex_lc;
					$img_upload_path = 'uploads/bussiness_register/' . $new_img_name;
					move_uploaded_file($tmp_name, $img_upload_path);

					// Insert into Database
					$sql = "INSERT into mms_supplier_attachments (msd_sup_code,msd_file_name,msd_file_path,msd_status,created_by,created_date,updated_by,updated_date) VALUES
				('$suppliercode','$new_img_name','$img_upload_path','A','$createdby','$createddate','NULL','NULL')";
					mysqli_query($con, $sql);
					echo '<script type="text/javascript">alert("File Uploaded Successfully!!"); location.href="profile.php";</script>';
					// var_dump();
					//return header("Location: profile.php");
				} else {
					$em = "You can't upload files of this type";
					return header("Location: profile.php?error=$em");
				}
			}
		} else {
			$em = "Unknown error occurred!";
			return header("Location: profile.php?error=$em");
		}
	} else {
		return header("Location: profile.php");
	}
}
