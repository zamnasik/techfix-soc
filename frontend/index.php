<?php include 'navbar.php'; ?>  <!-- Include Navigation Bar -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFix - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 id="headline">Seamless Procurement for Tech Repairs</h1>
            <p id="subtext">Compare, order, and track hardware supplies with real-time data—effortlessly.</p>
            <a href="signup.php" class="cta-button">Get Started</a>
        </div>
    </section>

    <!-- Features Section -->
<!-- Features Section with Cards -->
<section class="features">
    <div class="feature-card">
        <img src="images/quotation.png" alt="Quotation System">
        <h3>Quick Quotation System</h3>
        <p>Compare supplier prices in one place.</p>
    </div>
    <div class="feature-card">
        <img src="images/inventory.png" alt="Inventory Updates">
        <h3>Real-time Inventory Updates</h3>
        <p>Live stock tracking from suppliers.</p>
    </div>
    <div class="feature-card">
        <img src="images/order.png" alt="Automated Orders">
        <h3>Automated Order Placement</h3>
        <p>Place orders instantly with error-free processing.</p>
    </div>
</section>


<?php include 'footer.php'; ?>  <!-- Include Footer Section -->

<script src="js/script.js"></script>
</body>
</html>
