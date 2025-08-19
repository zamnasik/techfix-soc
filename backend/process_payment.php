<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'];
    $payment_method = $_POST['payment_method'];

    // Update payment status
    $query = "UPDATE payments SET status = 'Paid', payment_date = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Payment failed."]);
    }
}
?>
