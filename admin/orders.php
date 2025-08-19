<?php
include '../backend/db.php';
session_start();

// Check if Admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch orders with customer & supplier details
$query = "SELECT orders.*, 
                 customers.first_name AS customer_name, customers.email AS customer_email,
                 suppliers.first_name AS supplier_name, suppliers.email AS supplier_email
          FROM orders
          JOIN users AS customers ON orders.user_id = customers.id
          JOIN users AS suppliers ON orders.supplier_id = suppliers.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Management</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h2>Admin Panel - Order Management</h2>
        <button class="logout-btn" onclick="window.location.href='../backend/logout.php'">🚪 Logout</button>
    </div>

    <!-- Filters -->
    <div class="filter-container">
        <select id="statusFilter" onchange="filterOrders()">
            <option value="">📌 Filter by Status</option>
            <option value="Pending">Pending</option>
            <option value="Processing">Processing</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select>
    </div>

    <!-- Orders Table -->
    <div class="table-container">
        <table id="ordersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Supplier</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ordersBody">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="order-row">
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['customer_name'] . ' (' . $row['customer_email'] . ')' ?></td>
                        <td><?= $row['supplier_name'] . ' (' . $row['supplier_email'] . ')' ?></td>
                        <td>$<?= number_format($row['total_amount'], 2) ?></td>
                        <td class="status"><?= ucfirst($row['status']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                <button class="approve-btn" onclick="updateOrderStatus(<?= $row['id'] ?>, 'Processing')">✅ Approve</button>
                                <button class="cancel-btn" onclick="updateOrderStatus(<?= $row['id'] ?>, 'Cancelled')">❌ Cancel</button>
                            <?php } elseif ($row['status'] == 'Processing') { ?>
                                <button class="complete-btn" onclick="updateOrderStatus(<?= $row['id'] ?>, 'Completed')">✔ Mark as Completed</button>
                            <?php } ?>
                            <button class="view-btn" onclick="viewOrderDetails(<?= $row['id'] ?>)">📜 View</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../frontend/js/admin_orders.js"></script>
</body>
</html>
