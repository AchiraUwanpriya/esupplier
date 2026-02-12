<?php

include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $mobile = $_POST['mobile'];
  $supname = $_POST['supname'];
  $email = $_POST['email'];
  $supcat = $_POST['supcat'];
  $description = $_POST['description'];
  $address = $_POST['address'];

  // $mobileexists = "SELECT msd_mobileno FROM mms_supplier_pending_details WHERE msd_mobileno = '$mobile'";
  // $mobileexists = "SELECT count(msd_mobileno) msd_mobileno FROM mms_supplier_pending_details WHERE msd_mobileno = '$mobile'";

  //code below is for checking the entering number is existed or not =================================
  $query_count = "SELECT count(msd_mobileno) AS numbercount FROM mms_suppliers_details WHERE msd_mobileno = '$mobile'";
  $data_count = mysqli_query($con, $query_count) or die("database error:" . mysqli_error($con));
  //$row_count = sqlsrv_fetch_row($data_count);
  $row = mysqli_fetch_object($data_count);

  $query_count1 = "SELECT count(msd_mobileno) AS numbercount FROM mms_supplier_pending_details WHERE msd_mobileno = '$mobile'";
  $data_count1 = mysqli_query($con, $query_count1) or die("database error:" . mysqli_error($con));
  $row1 = mysqli_fetch_object($data_count1);

  // ================================= end code existing number checker
  
  if ($supname = $_POST['supname'] == "" || $mobile = $_POST['mobile'] == "" || $email = $_POST['email'] == "" || $supcat = $_POST['supcat'] == "" || $description = $_POST['description'] == "" || $address = $_POST['address'] == "" || $captcha = empty($_POST['g-recaptcha-response'] )) {
    die('<p style="color:red;text-align:center;">Please fill the fields!</p>');
  }

  // $secret_key = '6LeyhpcgAAAAAEdH8eXbOd2HGIPQbhB_jeeKYjlH';
  // // $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key .' 
  // // &response='.$_POST['g-recaptcha-response'].'');
  // $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
  // $response_data = json_decode($response);

  // if(!$response_data->success)
  // {
  //   die('Captcha verification failed');
  // }

  if ($row->numbercount != 0 || $row1->numbercount != 0) {
    // var_dump($mobileexists);
    // die('The mobile number is already exists!!!');
    die('<p style="color:red;text-align:center;">The mobile number is already exists!!!</p>');
  }  

  // if (!preg_match("/^[a-zA-Z ]+$/",$_POST['supname'])) {
    // die('Name must contain only Capital letters');
  //   die('<p style="color:red;text-align:center;">Name must contain only Capital letters</p>');
  // }

  if (!preg_match("/^[a-zA-Z ]+$/",$_POST['supcat'])) {
    var_dump($supcat);
    // die('Please enter a category without numbers');
    die('<p style="color:red;text-align:center;">Please enter a category without numbers</p>');
  }

  // if (!preg_match("/^[a-zA-Z ]+$/",$_POST['address'])) {
    if (preg_match("/^[0-9]+\s(\w)*(\W)(\s?)(\w)*(\W)(#[0-9])?(\W*)(\w)*(\W)(\s?)(\w)*(\s?)(\w)*+$/",$_POST['address'])) {
      
    // die('Please enter a correct address');
    die('<p style="color:red;text-align:center;">Please enter a correct address</p>');
  }

  if(strlen($_POST['mobile']) != 10) {
    // die('Please input a correct mobile number. The number must be minimum of 10 digits');
    die('<p style="color:red;text-align:center;">Please input a correct mobile number. The number must be minimum of 10 digits</p>');
  }

  if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
    // die('Please Enter Valid Email Address');
    die('<p style="color:red;text-align:center;">Please Enter Valid Email Address</p>');
  }

  else {
    $supname = $_POST['supname'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $supcat = $_POST['supcat'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    // $createdby = $_SESSION['User'];
		$createddate = date('Y-m-d');
    

    $uid = time();
    $query = "INSERT INTO mms_supplier_pending_details (msd_supplier_code,msd_supplier_name,msd_email_address,msd_mobileno,msd_supply_category,msd_supply_category_des,msd_address,msd_status,created_date) VALUES ($uid, UPPER('$supname'),'$email','$mobile','$supcat','$description',UPPER('$address'),'I','$createddate')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
      // echo "Successfully Registered! We will get back to you soon!";
      echo '<p style="color:green;text-align:center;">Successfully Registered! We will get back to you soon!</p>';
      // header('location:');
      header('Refresh: 0');
    }
    else {
      // echo "Please fill the fields!";
      echo '<p style="color:red;text-align:center;">Please fill the fields!</p>';

    }
  }
}
?>
