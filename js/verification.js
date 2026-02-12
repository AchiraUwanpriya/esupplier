function sendOTP() {
  $(".error").html("").hide();
  var number = $("#mobile").val();
  var snumber = $("#servicenumber").val();

  $("#loadbutton").show();
  $("#submit").prop("disabled", true);
  if ((number && number.length === 10) || (snumber && snumber.length === 7)) {
    var input = {
      mobile_number: number,
      service_number: snumber,
      action: "send_otp",
    };
    $.ajax({
      url: "controller.php",
      type: "POST",
      data: input,
      success: function (response) {
        //console.log('response', response);
        $("#loadbutton").hide();
        if (response === "block") {
          stop();
          $(".error").html("User Does Not Exists!!!");
          $(".error").show();
        } else if (response === "pending") {
          stop();
          $(".error").html("User Approval Is Pending!!!");
          $(".error").show();
        } else {
          $(".container").html(response);
        }
      },
    });
  } else {
    $("#loadbutton").hide();
    $(".error").html("Please enter a valid number!");
    $(".error").show();
    stop();
  }
}

function verifyOTP() {
  $(".error").html("").hide();
  $(".success").html("").hide();
  var otp = $("#mobileOtp").val();

  //
  if (otp.length == 5 && otp != null) {
    var input = {
      otp: otp,
      action: "verify_otp",
    };
    $.ajax({
      url: "controller.php",
      type: "POST",
      dataType: "json",
      data: input,
      success: function (response) {
        // console.log(response);
        if (response.type == "success") {
          // alert(response.message);
          // $(".").html(response.message)
          // $(".").show();
          if (response.status === "A") {
            window.location.href = "profile.php";
          } else {
            window.location.href = "dashboard.php";
          }
          // window.location.href = "dashboard.php";
        } else {
          // $("." + response.type).html(response.message);
          // $("." + response.type).show();
          $(".error").html("Please enter the OTP");
          $(".error").show();
          //
        }
      },
      // error : function() {
      // 	alert("ss");
      // }
    });
  } else {
    $(".error").html("You have entered wrong OTP.");
    $(".error").show();
  }
}
