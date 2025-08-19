<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    $query = "DELETE FROM orders WHERE id = ? AND status = 'Pending'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to cancel order."]);
    }
}
?>
