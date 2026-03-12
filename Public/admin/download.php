<?php
// Public/admin/download.php - Refactored
require_once '../../backend/common/config.php';

$FileNo = $_GET['FileNO'] ?? '';
// NOTE: Original code had a hardcoded supplier code.
$sup_code = $_GET['sup_code'] ?? '1674553571';

$tsql = "SELECT msd_file_path, msd_file_name FROM mms_supplier_attachments WHERE msd_sup_code = ?";
$stmt = mysqli_prepare($con, $tsql);
mysqli_stmt_bind_param($stmt, "s", $sup_code);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
    $msd_file_path = $row['msd_file_path'];
    $msd_file_name = $row['msd_file_name'];

    download_file($msd_file_path);
} else {
    die('File Not Found');
}

function download_file($file_path) {
    // Correct path relative to root if needed. 
    // msd_file_path usually stores paths relative to project root or absolute paths.
    // Assuming it needs adjustment if it was relative to root.
    $full_path = "../../" . $file_path;

    if (headers_sent()) die('Headers Sent');

    if (ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');

    if (file_exists($full_path)) {
        $fsize = filesize($full_path);
        $path_parts = pathinfo($full_path);
        $ext = strtolower($path_parts["extension"] ?? '');

        switch ($ext) {
            case "pdf": $ctype = "application/pdf"; break;
            case "exe": $ctype = "application/octet-stream"; break;
            case "zip": $ctype = "application/zip"; break;
            case "doc": $ctype = "application/msword"; break;
            case "xls": $ctype = "application/vnd.ms-excel"; break;
            case "ppt": $ctype = "application/vnd.ms-powerpoint"; break;
            case "gif": $ctype = "image/gif"; break;
            case "png": $ctype = "image/png"; break;
            case "jpeg":
            case "jpg": $ctype = "image/jpg"; break;
            default: $ctype = "application/force-download";
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=\"" . basename($full_path) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $fsize);
        ob_clean();
        flush();
        readfile($full_path);
    } else {
        die('File Not Found: ' . $full_path);
    }
}
?>
