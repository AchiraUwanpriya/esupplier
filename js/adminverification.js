function sendAdminOTP() {
  $(".error").html("").hide();
  var snumber = $("#servicenumber").val();
  
  var resolvedBaseURL = (typeof baseURL !== 'undefined') ? baseURL : "";

  $("#loadbutton").show();
  $("#submit").prop("disabled", true);
  if (snumber && snumber.length === 7) {
    var input = {
      service_number: snumber,
      action: "send_otp",
    };
    $.ajax({
      url: resolvedBaseURL + "adminapi.php",
      type: "POST",
      data: input,
      success: function (response) {
        $("#loadbutton").hide();
        if (response === "block") {
          $(".error").html("User Does Not Exists!!!").show();
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
    $(".error").html("Please enter a valid number!").show();
    $("#submit").prop("disabled", false);
  }
}

function verifyAdminOTP() {
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
      url: resolvedBaseURL + "adminapi.php",
      type: "POST",
      dataType: "json",
      data: input,
      success: function (response) {
        if (response.type == "success") {
          // Standardized direct redirect to Public/admin/adminview.php
          // We use a logic that avoids redundant ../ relative paths in the URL bar
          var path = window.location.pathname;
          var redirectPath = "";
          
          if (path.includes("/Public/admin/")) {
              redirectPath = "adminview.php";
          } else if (path.includes("/Public/")) {
              redirectPath = "admin/adminview.php";
          } else {
              redirectPath = "Public/admin/adminview.php";
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
