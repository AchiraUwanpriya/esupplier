<?php
// $serverName = "cdplc-mssql\cdplcsqldb";
// $connectionInfo= array("Database"=> "ESUP","UID"=>"ESUPPLIER", "PWD"=>"ESUPPLIER" );
// $con = sqlsrv_connect($serverName, $connectionInfo);

// if($con){
//     // echo "Connected.<br/>";

// }else{
//     echo"Connection Failed";
//     //die(print_r(sqlsrv_errors(), true));
//     die;
// }


// $servername = 'localhost';
// $username = 'u541226541_esupplier_cdl';
// $password = 'cdplc@eSupplier23';
// $dbname = 'u541226541_esup_db';
$servername = 'localhost:3306';
$username = 'root';
$password = '';
$dbname = 'esup';
//$con = mysqli_connect($servername, $username, $password, $dbname);
$con = new mysqli($servername, $username, $password, $dbname);

if ($con) {
    // echo "Connected.<br/>";

} else {
    echo "Connection Failed";
    die('Could not Connect My Sql:');
}

?>