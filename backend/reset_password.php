<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    $otp = $_POST['otp'];

    // Check if OTP is valid
    $query = "SELECT id FROM users WHERE otp_code = ? AND otp_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update password
        $updateQuery = "UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE otp_code = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $newPassword, $otp);
        if ($updateStmt->execute()) {
            echo json_encode(["success" => true, "message" => "Password reset successfully!"]);
        } else {
            echo json_encode(["error" => "Failed to reset password."]);
        }
    } else {
        echo json_encode(["error" => "Invalid OTP."]);
    }
}
?>
