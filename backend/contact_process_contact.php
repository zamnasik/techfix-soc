<?php
require 'db.php';

// Load PHPMailer manually
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "All required fields must be filled!";
        exit();
    }

    // Insert into Database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    
    if ($stmt->execute()) {
        // Email Sending Process
        $mail = new PHPMailer(true);
        
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Change for another provider
            $mail->SMTPAuth = true;
            $mail->Username = 'zamnasik@gmail.com';  // Your email address
            $mail->Password = '**** **** **** ****'; // Use **App Password** if using Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email Details
            $mail->setFrom('zamnasik@gmail.com', 'TechFix Support');
            $mail->addAddress($email, $name); // Send email to the form user
            $mail->Subject = "We Received Your Message - TechFix Support";
            $mail->Body = "Dear $name,\n\nThank you for reaching out to TechFix!\n\nWe have received your message and will get back to you shortly.\n\nYour Message:\n$subject\n$message\n\nBest Regards,\nTechFix Team";

            $mail->send();
            echo "Message sent successfully! Please check your email.";
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: Unable to send your message.";
    }

    $stmt->close();
}
?>
