<?php include 'navbar.php'; ?>  <!-- Include Navigation Bar -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TechFix</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="login-container">
    <div class="login-left">
        <img src="images/login-banner.jpg" alt="Login Image">
    </div>
    <div class="login-right">
        <h2>Welcome Back</h2>
        <form id="loginForm">
            <input type="email" id="email" name="email" placeholder="Email Address" required>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>
            <div class="remember-forgot">
                <input type="checkbox" id="rememberMe"> <label for="rememberMe">Remember Me</label>
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <button type="submit" id="login-btn" disabled>Login</button>
        </form>
        <p>✅ New here? <a href="signup.php">Sign up instead</a></p>
    </div>
</div>



<script src="js/login.js"></script>
<?php include 'footer.php'; ?>  <!-- Include Footer -->
</body>
</html>
