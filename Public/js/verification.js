function sendOTP() {
    var mobile = document.getElementById('mobile').value;
    
    if (mobile.length !== 10) {
        document.querySelector('.error').innerHTML = 'Please enter 10 digit mobile number';
        return false;
    }
    
    document.getElementById('loadbutton').style.display = 'block';
    
    $.ajax({
        type: "POST",
        url: "../backend/auth/auth_handler.php",
        data: {
            action: 'login',
            mobile: mobile
        },
        dataType: "json",
        success: function(response) {
            document.getElementById('loadbutton').style.display = 'none';
            if (response.success) {
                window.location.href = response.redirect;
            } else {
                document.querySelector('.error').innerHTML = response.message;
            }
        },
        error: function() {
            document.getElementById('loadbutton').style.display = 'none';
            document.querySelector('.error').innerHTML = 'Server error. Please try again.';
        }
    });
}