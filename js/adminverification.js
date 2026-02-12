function sendAdminOTP() {
  $(".error").html("").hide();
  var snumber = $("#servicenumber").val();

  $("#loadbutton").show();
  $("#submit").prop("disabled", true);
  if (snumber && snumber.length === 7) {
    var input = {
      service_number: snumber,
      action: "send_otp",
    };
    $.ajax({
      url: "adminapi.php",
      type: "POST",
      data: input,
      success: function (response) {
        //console.log('response', response);
        $("#loadbutton").hide();
        if (response === "block") {
          stop();
          $(".error").html("User Does Not Exists!!!");
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

function verifyAdminOTP() {
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
      url: "adminapi.php",
      type: "POST",
      dataType: "json",
      data: input,
      success: function (response) {
        // console.log(response);
        if (response.type == "success") {
          // alert(response.message);
          // $(".").html(response.message)
          // $(".").show();
          // if (response.status === "A") {
          //   window.location.href = "profile.php";
          // } else {
          //   window.location.href = "dashboard.php";
          // }
          window.location.href = "adminview.php";
        } else {
          $("." + response.type).html(response.message);
          $("." + response.type).show();
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
