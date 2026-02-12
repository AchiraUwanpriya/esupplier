<?php
session_start();
if (!isset($_SESSION['sup_code'])) {
	return header('Location: index.php');
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
			if ($img_size > 125000) {
				$em = "Sorry, your file is too large.";
				return header("Location: index.php?error=$em");
			} else {
				$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
				$img_ex_lc = strtolower($img_ex);

				$allowed_exs = array("docx", "xlsx");

				if (in_array($img_ex_lc, $allowed_exs)) {
					$new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
					$img_upload_path = 'materials_details/' . $new_img_name;
					move_uploaded_file($tmp_name, $img_upload_path);

					// Insert into Database
					$sql = "UPDATE mms_suppliers_details set msd_doc_url = '$new_img_name'
                WHERE msd_supplier_code = '$suppliercode'";
					mysqli_query($con, $sql);
					echo '<script type="text/javascript">alert("File Uploaded Successfully!!"); location.href="profile.php";</script>';
					var_dump();
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
