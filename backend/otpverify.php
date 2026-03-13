<?php
session_start();
?>
<?php
require_once 'common/config.php';

// Removed the block that redirects to dashboard.php based on $_POST['insert']
// because it was causing automatic redirects when OTP page loaded via AJAX
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="static/img/2.svg" />
  <link rel="stylesheet" href="static/css/login.css" />
  <title>eSupplier-CDPLC</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
  <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>

</head>

<body>
  <div class="container">
    <!-- <div class="error"></div> -->
    <div class="forms-container">
      <div class="signin-signup">


        <form id="frm-mobile-verification" class="sign-in-form">
          <!-- <center><img src="img/2.svg" style="height: 60px; width: 100%;"  alt=""></center>  -->
          <img class="mb-4" src="static/img/9.png" width="50%" alt="">
          <br><br>
          <h2 class="title">Enter Your OTP Number</h2>
          <p>We have sent a 5 digit otp number to your mobile.</p>

          <!-- <button type="button" class="btn btn-primary" onclick="fun()">click Me</button>
        <script type="text/javascript">
            function fun()
            {
            document.writeln("<input type='text' name='text'>")
            ;
            }

        </script> -->

          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="mobileOtp" class="form-input" placeholder="Enter the OTP" maxlength="5" />
          </div>

          <!-- timer -->
          <div style="display:flex;  flex-direction:row ; color: red;  font-size: 18px;">
            <div style="margin-right: 5px;">
              <p> Time Remaining:
              </p>
            </div>
            <div>
              <p id="timer"></p>
            </div>
          </div>

          <div class="error text-danger" style="font-weight: bold; color: red;"></div>
          <div class="success" style="color: green; font-weight: bold;"></div>
          <input id="verify" type="button" name="insert" class="btnVerify btn solid" value="Verify" onClick="verifyOTP()" />
          <!-- <input id="verify" type="button" class="btnVerify" value="Verify" onClick="verifyOTP();">	 -->

        </form>
      </div>
    </div>


    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <img class="mb-5 pb-4" src="static/img/dockyardlogo.png" width="50%" alt="">
          <p>
            Please use this LOGIN-IN to place a tender and select the categories as required to proceed !!!
          </p>
          <!-- <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button> -->
        </div>
        <img src="/esupplier/Public/static/img/loginimage.svg" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>Register to SIGN-In</h3>
          <p>
            If you have an account please use SIGN-IN to login and proceed the tender
          </p>
          <!-- <button class="btn transparent" id="sign-in-btn">
            Sign in
          </button> -->
        </div>
        <img src="/esupplier/Public/static/img/loginimage.svg" class="image" alt="" />
      </div>
    </div>
  </div>

  <!-- <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script> -->

  <!-- <script src="./static/js/jquery-3.3.1.min.js"></script> -->

  <script src="static/js/login.js"></script>
  <script src="static/js/showhideelement.js"></script>
  <script src="js/verification.js"></script>
  <!-- <script src="js/adminverification.js"></script> -->
  <script src="js/otpexpire.js"></script>
  
  <script>
    (function() {
      var timerEl = document.getElementById('timer');
      if (!timerEl) return;
      
      var duration = 90;
      var countdown = setInterval(function() {
        duration--;
        if (duration < 1) {
          clearInterval(countdown);
          alert('OTP session expired. Please try again.');
          window.location.reload();
        } else {
          var mins = parseInt(duration / 60);
          var secs = duration % 60;
          timerEl.innerText = mins + ":" + (secs < 10 ? "0" + secs : secs);
        }
      }, 1000);
      
      // Handle Enter key
      var otpInput = document.getElementById('mobileOtp');
      if (otpInput) {
        otpInput.addEventListener('keydown', function(e) {
          if (e.keyCode == 13 || e.key == 'Enter') {
            e.preventDefault();
            if (typeof verifyOTP === 'function') verifyOTP();
          }
        });
      }
    })();
  </script>