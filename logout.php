<?php 

session_start();
session_destroy();

header("Location: Public/Supplier/index.php");


?>