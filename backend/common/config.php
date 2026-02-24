<?php
// config.php

define('DB_HOST', 'localhost:3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'esup');

define('RECAPTCHA_SECRET', '6LeyhpcgAAAAAEdH8eXbOd2HGIPQbhB_jeeKYjlH');
define('RECAPTCHA_SITE', '6LeyhpcgAAAAAAwsDOsKlWMVpwvmorC6sJ6oLNRz');

// Your existing database connection
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>