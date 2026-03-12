<?php
// supRegistration.php - Refactored for Public/Supplier/
$__root = __DIR__ . '/../../';
require_once $__root . 'backend/common/config.php';
require_once $__root . 'backend/supplier/registration_queries.php';

$registrationQueries = new RegistrationQueries();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $mobile = $_POST['mobile'] ?? '';
    $supname = $_POST['supname'] ?? '';
    $email = $_POST['email'] ?? '';
    $supcat = $_POST['supcat'] ?? '';
    $description = $_POST['description'] ?? '';
    $address = $_POST['address'] ?? '';

    // Field validation
    if ($supname == "" || $mobile == "" || $email == "" || $supcat == "" || $description == "" || $address == "" || empty($_POST['g-recaptcha-response'])) {
        die('<p style="color:red;text-align:center;">Please fill the fields!</p>');
    }

    // Check if mobile exists using encapsulated logic
    if ($registrationQueries->checkMobileExists($mobile)) {
        die('<p style="color:red;text-align:center;">The mobile number is already exists!!!</p>');
    }

    // Category validation
    if (!preg_match("/^[a-zA-Z ]+$/", $supcat)) {
        die('<p style="color:red;text-align:center;">Please enter a category without numbers</p>');
    }

    // Address validation (simplified but similar to original)
    if (preg_match("/^[0-9]+\s(\w)*(\W)(\s?)(\w)*(\W)(#[0-9])?(\W*)(\w)*(\W)(\s?)(\w)*(\s?)(\w)*+$/", $address)) {
        die('<p style="color:red;text-align:center;">Please enter a correct address</p>');
    }

    // Mobile length
    if (strlen($mobile) != 10) {
        die('<p style="color:red;text-align:center;">Please input a correct mobile number. The number must be minimum of 10 digits</p>');
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('<p style="color:red;text-align:center;">Please Enter Valid Email Address</p>');
    }

    // Perform registration
    $success = $registrationQueries->registerSupplier([
        'supname' => $supname,
        'mobile' => $mobile,
        'email' => $email,
        'supcat' => $supcat,
        'description' => $description,
        'address' => $address
    ]);

    if ($success) {
        echo '<p style="color:green;text-align:center;">Successfully Registered! We will get back to you soon!</p>';
        header('Refresh: 2; URL=index.php'); // Redirect to login page after 2 seconds
    } else {
        echo '<p style="color:red;text-align:center;">Registration failed. Please try again later.</p>';
    }
}
?>
