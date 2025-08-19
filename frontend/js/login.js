$(document).ready(function () {
    $("#loginForm input").on("input", function () {
        let isValid = true;
        $("#loginForm input[required]").each(function () {
            if ($(this).val().trim() === "") {
                isValid = false;
            }
        });
        $("#login-btn").prop("disabled", !isValid);
    });

    $("#loginForm").on("submit", function (e) {
        e.preventDefault(); // Prevent page reload

        let formData = {
            email: $("#email").val(),
            password: $("#password").val(),
            rememberMe: $("#rememberMe").is(":checked") ? 1 : 0
        };

        $.ajax({
            type: "POST",
            url: "../backend/login_process.php", // Correct path to backend
            data: formData,
            dataType: "json",
            success: function (response) {
                console.log("🔥 AJAX Response Received:", response); // ✅ Debug AJAX Response
                if (response.success) {
                    console.log("🚀 Redirecting to:", response.redirect); // ✅ Debug Redirect URL
                    window.location.href = response.redirect;
                } else {
                    console.error("❌ Login Error:", response.error); // ❌ Print Error Message
                    alert(response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("❌ AJAX Error:", xhr.responseText); // ✅ Debug AJAX Error
                alert("Login failed. Please try again.");
            }
        });
        
        
    });
});

// Toggle Password Visibility
function togglePassword() {
    let passwordInput = document.getElementById("password");
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
}
