function sendOTP() {
    var mobile = document.getElementById('mobile').value;
    
    // Ensure baseURL is defined
    var resolvedBaseURL = (typeof baseURL !== 'undefined') ? baseURL : "";
    
    if (mobile.length !== 10) {
        document.querySelector('.error').innerHTML = 'Please enter 10 digit mobile number';
        return false;
    }
    
    document.getElementById('loadbutton').style.display = 'block';
    
    $.ajax({
        type: "POST",
        url: resolvedBaseURL + "backend/auth_controller.php",
        data: {
            action: 'send_otp',
            mobile_number: mobile
        },
        success: function(response) {
            document.getElementById('loadbutton').style.display = 'none';
            if (response === 'block') {
                document.querySelector('.error').innerHTML = 'User does not exist or account is inactive!';
            } else if (response === 'pending') {
                document.querySelector('.error').innerHTML = 'Your account is pending approval.';
            } else {
                // Success - inject the OTP verification form returned by backend
                // Use jQuery .html() to ensure script tags in response are executed
                $(".container").html(response);
                
                // Fix relative paths in injected content if necessary
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
            document.getElementById('loadbutton').style.display = 'none';
            document.querySelector('.error').innerHTML = 'Server error. Please try again.';
        }
    });
}

function verifyOTP() {
    var otp = document.getElementById('mobileOtp').value;
    var resolvedBaseURL = (typeof baseURL !== 'undefined') ? baseURL : "";
    
    if (otp.length !== 5) {
        document.querySelector('.error').innerHTML = 'Please enter 5 digit OTP';
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: resolvedBaseURL + "backend/auth_controller.php",
        data: {
            action: 'verify_otp',
            otp: otp
        },
        dataType: "json",
        success: function(response) {
            if (response.type === 'success') {
                window.location.href = resolvedBaseURL + 'Public/Supplier/dashboard.php';
            } else {
                document.querySelector('.error').innerHTML = response.message;
            }
        },
        error: function() {
            document.querySelector('.error').innerHTML = 'Verification failed. Please try again.';
        }
    });
}