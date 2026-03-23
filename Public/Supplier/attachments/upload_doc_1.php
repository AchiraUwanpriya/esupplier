<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
	return header('Location: ../profile.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$suppliercode = $_SESSION['sup_code'];
	$createdby = $suppliercode;
	$createddate = date('Y-m-d');

	if (isset($_POST['submit']) && isset($_FILES['my_image1'])) {
		require_once __DIR__ . '/../../../backend/common/config.php';

		$img_name = $_FILES['my_image1']['name'];
		$img_size = $_FILES['my_image1']['size'];
		$tmp_name = $_FILES['my_image1']['tmp_name'];
		$error = $_FILES['my_image1']['error'];

		if ($error === 0) {
			if ($img_size > 20000000) {
				$em = "Sorry, your file is too large.";
				return header("Location: ../profile.php?error1=$em");
			} else {
				$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
				$img_ex_lc = strtolower($img_ex);
				$allowed_exs = array("jpg", "jpeg", "png", "pdf");

				if (in_array($img_ex_lc, $allowed_exs)) {
					$new_img_name = uniqid("BR-", true) . '.' . $img_ex_lc;
					$upload_dir = '../../uploads/bussiness_register/';
					if (!is_dir($upload_dir)) {
						mkdir($upload_dir, 0777, true);
					}
					$img_upload_path = $upload_dir . $new_img_name;

					if (move_uploaded_file($tmp_name, $img_upload_path)) {
						// Insert into Database
						$sql = "INSERT into mms_supplier_attachments (msd_sup_code,msd_file_name,msd_file_path,msd_status,created_by,created_date,updated_by,updated_date) VALUES
				('$suppliercode','$new_img_name','$img_upload_path','A','$createdby','$createddate','NULL','NULL')";
						mysqli_query($con, $sql);
						echo '<script type="text/javascript">alert("Business Registration Uploaded Successfully!!"); location.href="../profile.php";</script>';
					} else {
						$em = "Failed to move uploaded file. Check folder permissions.";
						return header("Location: ../profile.php?error1=$em");
					}
				} else {
					$em = "You can't upload files of this type";
					return header("Location: ../profile.php?error1=$em");
				}
			}
		} else {
			$em = "Unknown error occurred!";
			return header("Location: ../profile.php?error1=$em");
		}
	} else {
		return header("Location: ../profile.php");
	}
}
?>
