<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to request a quotation.";
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Check if product exists
$query = "SELECT * FROM inventory WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $supplier_id = $row['supplier_id'];
    $product_name = $row['product_name'];

    // Insert into quotations table
    $insertQuery = "INSERT INTO quotations (user_id, supplier_id, product_name, requested_price, status) VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iisd", $user_id, $supplier_id, $product_name, $row['price']);

    if ($stmt->execute()) {
        echo "Quotation request sent for $product_name!";
    } else {
        echo "Failed to send quotation request.";
    }
} else {
    echo "Invalid product.";
}
?>
