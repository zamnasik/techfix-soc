<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'db.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

date_default_timezone_set('Asia/Colombo'); // Set timezone to Sri Lanka

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(["error" => "No account found with this email."]);
        exit();
    }

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP expires in 10 minutes

    // Store OTP in the database
    $updateQuery = "UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sss", $otp, $expiry, $email);
    
    if ($updateStmt->execute()) {
        // Send OTP via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zamnasik@gmail.com';
            $mail->Password = '**** **** **** ****';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('zamnasik@gmail.com', 'TechFix');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "<p>Your OTP code is: <b>$otp</b></p>
                           <p>This code is valid until: <b>$expiry</b></p>";

            if ($mail->send()) {
                echo json_encode(["success" => true, "message" => "OTP sent to your email."]);
            } else {
                echo json_encode(["error" => "Failed to send OTP."]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Email sending error: " . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(["error" => "Failed to generate OTP."]);
    }
}
?>
