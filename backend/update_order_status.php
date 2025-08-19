<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Order status updated."]);
    } else {
        echo json_encode(["error" => "Failed to update order status."]);
    }
}
?>
