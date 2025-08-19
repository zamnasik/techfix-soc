<?php
require 'db.php';

$query = "SELECT inventory.*, users.first_name AS supplier_name FROM inventory
          JOIN users ON inventory.supplier_id = users.id WHERE inventory.status = 'Approved'";
$result = $conn->query($query);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
