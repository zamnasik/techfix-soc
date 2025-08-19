<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'db.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$ownerEmail = "nasikbackup@gmail.com"; // Set your email for Admin approval

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $token = md5(uniqid($email, true)); // Unique token for verification

    // Debugging log
    file_put_contents("debug.log", "Received signup request for: $email, Role: $role\n", FILE_APPEND);

    $query = "INSERT INTO users (first_name, last_name, email, phone, password, role, token, verified) 
              VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phone, $password, $role, $token);

    if ($stmt->execute()) {
        file_put_contents("debug.log", "Database insertion successful\n", FILE_APPEND);

        // Email setup
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zamnasik@gmail.com'; // Replace with your email
            $mail->Password = 'pjor bttp pywk qvag'; // Replace with your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->isHTML(true);

            if ($role == "Admin") {
                // Send email to the **Owner** for Admin approval
                $mail->setFrom('nasikbackup@gmail.com', 'TechFix Admin Request');
                $mail->addAddress($ownerEmail);

                $mail->Subject = 'New Admin Signup Request - TechFix';
                $mail->Body = "<h2>Admin Signup Request</h2>
                               <p>Name: $firstName $lastName</p>
                               <p>Email: $email</p>
                               <p>Phone: $phone</p>
                               <p>To approve this admin, manually verify the account in the database.</p>";

                file_put_contents("debug.log", "Admin signup request email sent to owner\n", FILE_APPEND);
            } else {
                // Send verification email to the **User/Supplier**
                $mail->setFrom('zamnasik@gmail.com', 'TechFix');
                $mail->addAddress($email);

                $mail->Subject = 'Verify Your Email - TechFix';
                $mail->Body = "<h2>Welcome to TechFix, $firstName!</h2>
                               <p>Please confirm your email by clicking the link below:</p>
                               <a href='http://localhost/techfix-soc/backend/verify_email.php?token=$token'>Verify Email</a>";

                file_put_contents("debug.log", "User verification email sent\n", FILE_APPEND);
            }

            if ($mail->send()) {
                echo json_encode(["message" => "Signup successful! Please check your email to verify your account."]);
            } else {
                file_put_contents("debug.log", "Email sending failed\n", FILE_APPEND);
                echo json_encode(["error" => "Signup successful, but email could not be sent."]);
            }
        } catch (Exception $e) {
            file_put_contents("debug.log", "PHPMailer Error: " . $mail->ErrorInfo . "\n", FILE_APPEND);
            echo json_encode(["error" => "Signup successful, but email sending failed."]);
        }
    } else {
        file_put_contents("debug.log", "Database insertion failed: " . $conn->error . "\n", FILE_APPEND);
        echo json_encode(["error" => "Signup failed. Try again."]);
    }
}
?>
