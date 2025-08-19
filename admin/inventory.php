<?php
include '../backend/db.php';
session_start();

// Check if Admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch inventory items
$query = "SELECT inventory.*, users.first_name, users.last_name, users.email 
          FROM inventory 
          JOIN users ON inventory.supplier_id = users.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Inventory Management</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h2>Admin Panel - Inventory Management</h2>
        <button class="logout-btn" onclick="window.location.href='../backend/logout.php'">🚪 Logout</button>
    </div>

    <!-- Search & Filters -->
    <div class="filter-container">
        <input type="text" id="searchBox" placeholder="🔍 Search by Product Name..." onkeyup="filterInventory()">
        <select id="statusFilter" onchange="filterInventory()">
            <option value="">📌 Filter by Status</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>
        <button onclick="showLowStock()">⚠️ Low Stock</button>
        <select id="sortBy" onchange="sortInventory()">
            <option value="">🔄 Sort By</option>
            <option value="stock">Stock (Low to High)</option>
            <option value="price">Price (Low to High)</option>
        </select>
    </div>

    <!-- Inventory Table -->
    <div class="table-container">
        <table id="inventoryTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="inventoryBody">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="inventory-row <?= ($row['stock'] <= 5) ? 'low-stock' : '' ?>">
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['first_name'] . ' ' . $row['last_name'] . ' (' . $row['email'] . ')' ?></td>
                        <td><?= $row['product_name'] ?></td>
                        <td class="stock"><?= $row['stock'] ?></td>
                        <td class="price">$<?= number_format($row['price'], 2) ?></td>
                        <td class="status"><?= ucfirst($row['status']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                <button class="approve-btn" onclick="updateStockStatus(<?= $row['id'] ?>, 'Approved')">✅ Approve</button>
                                <button class="reject-btn" onclick="updateStockStatus(<?= $row['id'] ?>, 'Rejected')">❌ Reject</button>
                            <?php } ?>
                            <button class="delete-btn" onclick="deleteStock(<?= $row['id'] ?>)">🗑 Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../frontend/js/admin_inventory.js"></script>
</body>
</html>
