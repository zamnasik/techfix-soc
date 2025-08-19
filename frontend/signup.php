<?php include 'navbar.php'; ?>  <!-- Include Navigation Bar -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | TechFix</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="signup-container">
    <div class="signup-left">
        <img src="images/signup-banner.jpg" alt="Signup Image">
    </div>
    <div class="signup-right">
        <h2>Create Your Account</h2>
        <form id="signupForm">
            <div class="input-group">
                <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
            </div>
            <input type="email" id="email" name="email" placeholder="Email Address" required>
            <input type="text" id="phone" name="phone" placeholder="Phone Number (e.g., +94 712345678)" required>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
            </div>
            <div class="password-strength">
                <span id="strength-text"></span>
                <div id="strength-meter"></div>
            </div>
            <select id="role" name="role">
                <option value="Customer">Customer</option>
                <option value="Supplier">Supplier</option>
                <option value="Admin">Admin</option>
            </select>
            <div class="checkbox-group">
                <input type="checkbox" id="terms" required>
                <label for="terms">I agree to the <a href="#">Terms & Privacy Policy</a></label>
            </div>
            <button type="submit" id="signup-btn" disabled>Sign Up</button>
        </form>
        <p>✅ Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<script src="js/signup.js"></script>
<?php include 'footer.php'; ?>  <!-- Include Footer -->
</body>
</html>
