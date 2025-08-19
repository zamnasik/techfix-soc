<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $supplier_id = $_POST['supplier_id'];
    $requested_price = $_POST['requested_price'];

    // Fetch product name from inventory
    $query = "SELECT product_name FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_name);
    $stmt->fetch();
    $stmt->close();

    // Insert into quotations table
    $insertQuery = "INSERT INTO quotations (user_id, supplier_id, product_name, requested_price, status) VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iisd", $user_id, $supplier_id, $product_name, $requested_price);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to request quotation"]);
    }
}
?>
