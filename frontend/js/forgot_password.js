function sendOTP() {
    let email = $("#email").val().trim();
    if (email === "") {
        alert("Please enter your email.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "../backend/send_otp.php",
        data: { email: email },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                alert(response.message);
                $("#step1").hide();
                $("#step2").show();
            } else {
                alert(response.error);
            }
        },
        error: function () {
            alert("Error processing request. Try again.");
        }
    });
}

function verifyOTP() {
    let otp = $("#otp").val().trim();
    if (otp === "") {
        alert("Please enter the OTP.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "../backend/verify_otp.php",
        data: { otp: otp },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#step2").hide();
                $("#step3").show();
            } else {
                alert(response.error);
            }
        }
    });
}

function resetPassword() {
    let newPassword = $("#newPassword").val().trim();
    let confirmPassword = $("#confirmPassword").val().trim();
    let otp = $("#otp").val().trim();

    if (newPassword !== confirmPassword) {
        alert("Passwords do not match!");
        return;
    }

    $.ajax({
        type: "POST",
        url: "../backend/reset_password.php",
        data: { newPassword: newPassword, otp: otp },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#step3").hide();
                $("#step4").show();
                setTimeout(() => { window.location.href = "login.php"; }, 3000);
            } else {
                alert(response.error);
            }
        }
    });
}
