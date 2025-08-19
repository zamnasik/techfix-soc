<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $supplierId = $_POST['supplier_id'];
    $product = $_POST['product'];
    $requestedPrice = $_POST['requested_price'];
    $status = 'Pending';

    $query = "INSERT INTO quotations (user_id, supplier_id, product_name, requested_price, status) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisss", $userId, $supplierId, $product, $requestedPrice, $status);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Quotation request submitted successfully!"]);
    } else {
        echo json_encode(["error" => "Failed to submit quotation request."]);
    }
}
?>
