<?php

include 'config.php';

$FileNo=$_GET['FileNO'];

$tsql = "SELECT msd_file_path,msd_file_name FROM mms_supplier_attachments WHERE msd_sup_code = '1674553571'";
$stmt = mysqli_query($con, $tsql);

while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {

    var_dump($msd_file_path);
    var_dump($msd_file_name);

//Use Mysql Query to find the 'full path' of file using $FileNo.
// I Assume $FilePaths as 'Full File Path'.

download_file($msd_file_name);

function download_file($msd_file_path)
{
  if( headers_sent() )
    die('Headers Sent');


  if(ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');


  if( file_exists($msd_file_path) )
  {

    $fsize = filesize($msd_file_path);
    $path_parts = pathinfo($msd_file_path);
    $ext = strtolower($path_parts["extension"]);

    switch ($ext) 
    {
      case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/zip"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      default: $ctype="application/force-download";
    }

    header("Pragma: public"); 
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false); 
    header("Content-Type: $ctype");
    header("Content-Disposition: attachment; filename=\"".basename($msd_file_path)."\";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$fsize);
    ob_clean();
    flush();
    readfile( $msd_file_path );

  } 
  else
    die('File Not Found');
}
}
?>