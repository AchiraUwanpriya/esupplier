<?php
// backend/common/config.php

// Database Credentials
$db_host = 'localhost:3306';
$db_user = 'root';
$db_pass = '';
$db_name = 'esup';

// Define constants using the if(!defined(...)) pattern
if (!defined('DB_HOST')) define('DB_HOST', $db_host);
if (!defined('DB_USER')) define('DB_USER', $db_user);
if (!defined('DB_PASS')) define('DB_PASS', $db_pass);
if (!defined('DB_NAME')) define('DB_NAME', $db_name);

// reCAPTCHA Keys
if (!defined('RECAPTCHA_SECRET')) define('RECAPTCHA_SECRET', '6LeyhpcgAAAAAEdH8eXbOd2HGIPQbhB_jeeKYjlH');
if (!defined('RECAPTCHA_SITE')) define('RECAPTCHA_SITE', '6LeyhpcgAAAAAAwsDOsKlWMVpwvmorC6sJ6oLNRz');

// Establish Connection
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>