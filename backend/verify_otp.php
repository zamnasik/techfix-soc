<?php
require 'db.php';
date_default_timezone_set('Asia/Colombo'); // Ensure correct time zone

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];

    // Check if OTP exists and is still valid
    $query = "SELECT id, otp_expiry FROM users WHERE otp_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $current_time = date("Y-m-d H:i:s");

        if ($user['otp_expiry'] >= $current_time) {
            echo json_encode(["success" => true, "message" => "OTP verified."]);
        } else {
            echo json_encode(["error" => "OTP has expired. Please request a new one."]);
        }
    } else {
        echo json_encode(["error" => "Invalid OTP."]);
    }
}
?>
