$(document).ready(function () {
    // Live validation
    $("#signupForm input").on("input", function () {
        let isValid = true;
        $("#signupForm input[required]").each(function () {
            if ($(this).val().trim() === "") {
                isValid = false;
            }
        });

        $("#signup-btn").prop("disabled", !isValid);
    });

    // Password Strength Checker
    $("#password").on("input", function () {
        let password = $(this).val();
        let strengthText = $("#strength-text");
        let strengthMeter = $("#strength-meter");

        if (password.length < 6) {
            strengthText.text("Weak").css("color", "red");
            strengthMeter.css("background", "red");
        } else if (password.length < 10) {
            strengthText.text("Medium").css("color", "orange");
            strengthMeter.css("background", "orange");
        } else {
            strengthText.text("Strong").css("color", "green");
            strengthMeter.css("background", "green");
        }
    });
});

$(document).ready(function () {
    $("#signupForm").on("submit", function (e) {
        e.preventDefault(); // Prevent page reload

        let formData = {
            firstName: $("#firstName").val(),
            lastName: $("#lastName").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            password: $("#password").val(),
            confirmPassword: $("#confirmPassword").val(),
            role: $("#role").val()
        };

        // Basic validation before sending request
        if (formData.password !== formData.confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        // Send AJAX request
        $.ajax({
            type: "POST",
            url: "../backend/signup_process.php", // Correct path to backend
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.message) {
                    alert(response.message);
                    window.location.href = "login.php"; // Redirect to login after signup
                } else {
                    alert(response.error);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error: ", error);
                alert("Signup failed. Please try again.");
            }
        });
    });
});
