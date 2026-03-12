function sendOTP() {
  $(".error").html("").hide();
  var number = $("#mobile").val();
  var snumber = $("#servicenumber").val();
  
  var resolvedBaseURL = (typeof baseURL !== 'undefined') ? baseURL : "";

  $("#loadbutton").show();
  $("#submit").prop("disabled", true);
  if ((number && number.length === 10) || (snumber && snumber.length === 7)) {
    var input = {
      mobile_number: number,
      service_number: snumber,
      action: "send_otp",
    };
    $.ajax({
      url: resolvedBaseURL + "controller.php",
      type: "POST",
      data: input,
      success: function (response) {
        $("#loadbutton").hide();
        if (response === "block") {
          stop();
          $(".error").html("User Does Not Exists!!!");
          $(".error").show();
          $("#submit").prop("disabled", false);
        } else if (response === "pending") {
          stop();
          $(".error").html("User Approval Is Pending!!!");
          $(".error").show();
          $("#submit").prop("disabled", false);
        } else {
          $(".container").html(response);
          // Fix relative paths in injected content
          $(".container").find("img, script, link").each(function() {
            var attr = $(this).is("img") || $(this).is("script") ? "src" : "href";
            var val = $(this).attr(attr);
            if (val && !val.startsWith("http") && !val.startsWith("/") && !val.startsWith("..") && !val.startsWith(".")) {
               $(this).attr(attr, resolvedBaseURL + val);
            }
          });
        }
      },
      error: function() {
        $("#loadbutton").hide();
        $("#submit").prop("disabled", false);
      }
    });
  } else {
    $("#loadbutton").hide();
    $(".error").html("Please enter a valid number!");
    $(".error").show();
    $("#submit").prop("disabled", false);
  }
}

function verifyOTP() {
  $(".error").html("").hide();
  $(".success").html("").hide();
  var otp = $("#mobileOtp").val();
  
  var resolvedBaseURL = (typeof baseURL !== 'undefined') ? baseURL : "";

  if (otp && otp.length == 5) {
    var input = {
      otp: otp,
      action: "verify_otp",
    };
    $.ajax({
      url: resolvedBaseURL + "controller.php",
      type: "POST",
      dataType: "json",
      data: input,
      success: function (response) {
        if (response.type == "success") {
          var targetName = (response.status === "A") ? "profile.php" : "dashboard.php";
          
          // Clean redirect logic
          var path = window.location.pathname;
          var redirectPath = "";
          
          if (path.includes("/Public/")) {
              redirectPath = "../" + targetName;
          } else {
              redirectPath = targetName;
          }
          
          window.location.href = redirectPath;
        } else {
          $(".error").html(response.message || "Verification failed").show();
        }
      },
      error: function() {
        $(".error").html("Connection error").show();
      }
    });
  } else {
    $(".error").html("Please enter a valid OTP.").show();
  }
}
