<?php
require 'db.php';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Order_Report.xls");

echo "ID\tUser\tSupplier\tTotal Amount\tStatus\n";

$query = "SELECT orders.*, 
                 users.first_name AS user_name, users.email AS user_email,
                 suppliers.first_name AS supplier_name, suppliers.email AS supplier_email
          FROM orders
          JOIN users ON orders.user_id = users.id
          JOIN users AS suppliers ON orders.supplier_id = suppliers.id";

$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    echo "{$row['id']}\t{$row['user_name']} ({$row['user_email']})\t{$row['supplier_name']} ({$row['supplier_email']})\t{$row['total_amount']}\t{$row['status']}\n";
}
?>
