<?php include 'navbar.php'; ?>  <!-- Include Navigation Bar -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | TechFix</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="forgot-password-container">
    <div class="forgot-password-box">
        <h2>Reset Your Password</h2>
        
        <!-- Step 1: Enter Email -->
        <div id="step1">
            <p>Enter your registered email to receive a reset code.</p>
            <input type="email" id="email" placeholder="📧 Email Address" required>
            <button onclick="sendOTP()">Send OTP</button>
        </div>

        <!-- Step 2: Enter OTP -->
        <div id="step2" style="display: none;">
            <p>Enter the OTP sent to your email.</p>
            <input type="text" id="otp" placeholder="🔑 Enter OTP" required>
            <button onclick="verifyOTP()">Verify OTP</button>
        </div>

        <!-- Step 3: Reset Password -->
        <div id="step3" style="display: none;">
            <p>Enter a new password.</p>
            <input type="password" id="newPassword" placeholder="🔒 New Password" required>
            <input type="password" id="confirmPassword" placeholder="🔒 Confirm Password" required>
            <button onclick="resetPassword()">Reset Password</button>
        </div>

        <!-- Success Message -->
        <div id="step4" style="display: none;">
            <p>✅ Your password has been reset successfully! Redirecting to login...</p>
        </div>
    </div>
</div>

<script src="js/forgot_password.js"></script>
<?php include 'footer.php'; ?>  <!-- Include Footer -->
</body>
</html>
