<?php
session_start();
// Root of the project (three levels up from Public/Supplier/attachments/)
$__root = __DIR__ . '/../../../';

if (!isset($_SESSION['sup_code'])) {
    return header('Location: ../../../index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suppliercode = $_SESSION['sup_code'];

    if (isset($_POST['submit']) && isset($_FILES['my_image'])) {
        require_once $__root . 'backend/common/config.php';
        require_once $__root . 'backend/supplier/profile_queries.php';
        
        $profileQueries = new ProfileQueries();

        $img_name = $_FILES['my_image']['name'];
        $img_size = $_FILES['my_image']['size'];
        $tmp_name = $_FILES['my_image']['tmp_name'];
        $error = $_FILES['my_image']['error'];

        if ($error === 0) {
            if ($img_size > 1250000) { // Normalized size check if needed, original was 125000 (125KB)
                $em = "Sorry, your file is too large.";
                return header("Location: ../profile.php?error=$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("docx", "xlsx", "pdf", "jpg", "jpeg", "png");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    // Path relative to this file
                    $img_upload_path = 'materials_details/' . $new_img_name;
                    
                    if (!is_dir('materials_details')) {
                        mkdir('materials_details', 0777, true);
                    }
                    
                    if (move_uploaded_file($tmp_name, $img_upload_path)) {
                        // Update Database
                        $success = $profileQueries->updateMaterialDoc($suppliercode, $new_img_name);
                        if ($success) {
                            echo '<script type="text/javascript">alert("File Uploaded Successfully!!"); location.href="../profile.php";</script>';
                        } else {
                            $em = "Database update failed.";
                            return header("Location: ../profile.php?error=$em");
                        }
                    } else {
                        $em = "Failed to move uploaded file.";
                        return header("Location: ../profile.php?error=$em");
                    }
                } else {
                    $em = "You can't upload files of this type";
                    return header("Location: ../profile.php?error=$em");
                }
            }
        } else {
            $em = "Unknown error occurred!";
            return header("Location: ../profile.php?error=$em");
        }
    } else {
        return header("Location: ../profile.php");
    }
}
?>
