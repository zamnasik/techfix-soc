<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $supplier_id = $_POST['supplier_id'];
    $quantity = $_POST['quantity'];

    // Fetch product details
    $query = "SELECT product_name, price FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_name, $price);
    $stmt->fetch();
    $stmt->close();

    $total_price = $price * $quantity;

    // Insert order
    $insertQuery = "INSERT INTO orders (user_id, supplier_id, product_name, quantity, total_price, status) VALUES (?, ?, ?, ?, ?, 'Processing')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iisid", $user_id, $supplier_id, $product_name, $quantity, $total_price);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to place order"]);
    }
}
?>
