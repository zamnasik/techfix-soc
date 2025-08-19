<?php include 'navbar.php'; ?>  <!-- Include Navigation Bar -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services | TechFix</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>TechFix Services</h1>
        <p>Reliable & Fast IT Solutions for Everyone</p>
        <a href="contact.php" class="cta-btn">Get a Quote</a>
    </div>
</section>

<!-- Services Overview -->
<section class="services">
    <h2>Our Key Services</h2>
    <div class="service-container">
        <div class="service-card" data-aos="fade-up">
            <i class="fas fa-tools"></i>
            <h3>Device Repairs & Upgrades</h3>
            <p>We repair & upgrade laptops, PCs, and mobile devices efficiently.</p>
        </div>
        <div class="service-card" data-aos="fade-up">
            <i class="fas fa-desktop"></i>
            <h3>Custom PC Builds</h3>
            <p>We build high-performance gaming and workstation PCs.</p>
        </div>
        <div class="service-card" data-aos="fade-up">
            <i class="fas fa-shopping-cart"></i>
            <h3>Quotation & Procurement</h3>
            <p>Compare suppliers & manage tech orders in real-time.</p>
        </div>
        <div class="service-card" data-aos="fade-up">
            <i class="fas fa-headset"></i>
            <h3>IT Consulting & Support</h3>
            <p>Expert IT solutions & troubleshooting for businesses.</p>
        </div>
        <div class="service-card" data-aos="fade-up">
            <i class="fas fa-network-wired"></i>
            <h3>Networking & Security</h3>
            <p>Secure your network with advanced firewall protection.</p>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose">
    <h2>Why Choose TechFix?</h2>
    <div class="why-container">
        <div class="why-item" data-aos="fade-right">
            <i class="fas fa-bolt"></i>
            <h3>Fast & Efficient</h3>
            <p>We deliver fast turnarounds without compromising quality.</p>
        </div>
        <div class="why-item" data-aos="fade-left">
            <i class="fas fa-shield-alt"></i>
            <h3>Reliable & Secure</h3>
            <p>Our solutions prioritize security & reliability.</p>
        </div>
        <div class="why-item" data-aos="fade-right">
            <i class="fas fa-dollar-sign"></i>
            <h3>Affordable Pricing</h3>
            <p>Get high-quality service at a cost-effective price.</p>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta">
    <h2>Ready to Get Started?</h2>
    <a href="contact.php" class="cta-btn">Contact Us Now</a>
</section>

<script>
    AOS.init();
</script>

<?php include 'footer.php'; ?>  <!-- Include Footer -->
</body>
<script>
    AOS.init();
</script>

</html>
