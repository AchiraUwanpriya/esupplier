// Set base URL for AJAX calls from Public folder
var baseURL = "../";

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
      url: baseURL + "adminapi.php",
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
          // Insert the HTML into the container
          $(".container").html(response);

          // Fix all relative image paths by prepending baseURL
          $(".container")
            .find("img")
            .each(function () {
              var src = $(this).attr("src");
              if (
                src &&
                !src.startsWith("http") &&
                !src.startsWith("/") &&
                !src.startsWith("..")
              ) {
                $(this).attr("src", baseURL + src);
              }
            });

          // Fix CSS and JS paths
          $(".container")
            .find("link")
            .each(function () {
              var href = $(this).attr("href");
              if (
                href &&
                !href.startsWith("http") &&
                !href.startsWith("/") &&
                !href.startsWith("..")
              ) {
                $(this).attr("href", baseURL + href);
              }
            });

          $(".container")
            .find("script")
            .each(function () {
              var src = $(this).attr("src");
              if (
                src &&
                !src.startsWith("http") &&
                !src.startsWith("/") &&
                !src.startsWith("..")
              ) {
                $(this).attr("src", baseURL + src);
              }
            });
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
      url: baseURL + "adminapi.php",
      type: "POST",
      dataType: "json",
      data: input,
      success: function (response) {
        //console.log('response', response);
        if (response.type == "success") {
          $(".success").html(response.message);
          $(".success").show();
          setTimeout(function () {
            window.location.href = baseURL + "adminview.php";
          }, 2000);
        } else {
          $(".error").html(response.message);
          $(".error").show();
        }
      },
      error: function () {
        $(".error").html("An error occurred.");
        $(".error").show();
      },
    });
  } else {
    $(".error").html("Please enter a valid OTP!");
    $(".error").show();
  }
}
