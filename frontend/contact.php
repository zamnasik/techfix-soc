<?php include 'navbar.php'; ?>  <!-- Include Navigation Bar -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | TechFix</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="hero-content">
        <h1>Contact TechFix</h1>
        <p>We’d love to hear from you! Reach out for any queries.</p>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="contact-container">
    <div class="contact-form">
        <h2>Send Us a Message</h2>
        <form action="../backend/contact_process_contact.php" method="POST" id="contactForm">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number (Optional)">
            <select name="subject" required>
                <option value="">Select Subject</option>
                <option value="General Inquiry">General Inquiry</option>
                <option value="Service Request">Service Request</option>
                <option value="Quotation Request">Quotation Request</option>
                <option value="Other">Other</option>
            </select>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
    
    <div class="contact-details">
        <h2>Contact Information</h2>
        <p><i class="fas fa-map-marker-alt"></i> 123 Tech Street, Colombo, Sri Lanka</p>
        <p><i class="fas fa-phone"></i> +94 77 123 4567</p>
        <p><i class="fas fa-envelope"></i> support@techfix.com</p>
        <p><i class="fas fa-clock"></i> Mon - Fri: 9 AM - 6 PM</p>
        <p><i class="fas fa-clock"></i> Sat - Sun: Closed</p>
    </div>
</section>

<!-- Google Map (Optional) -->
<section class="map">
    <h2>Find Us on Google Maps</h2>
    <iframe 
        src="https://www.google.com/maps/embed?pb=..." 
        width="100%" height="300" frameborder="0" allowfullscreen>
    </iframe>
</section>

<script>
    AOS.init();
</script>

<?php include 'footer.php'; ?>  <!-- Include Footer -->
</body>
</html>
