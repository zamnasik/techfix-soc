<?php
include '../backend/db.php';
session_start();

// Check if Admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch quotations with user and supplier details
$query = "SELECT quotations.*, 
                 users.first_name AS user_name, users.email AS user_email,
                 suppliers.first_name AS supplier_name, suppliers.email AS supplier_email
          FROM quotations
          JOIN users ON quotations.user_id = users.id
          JOIN users AS suppliers ON quotations.supplier_id = suppliers.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quotation Management</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h2>Admin Panel - Quotations</h2>
        <button class="logout-btn" onclick="window.location.href='../backend/logout.php'">🚪 Logout</button>
    </div>

    <!-- Quotation Table -->
    <div class="table-container">
        <table id="quotationsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Supplier</th>
                    <th>Product</th>
                    <th>Requested Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="quotationsBody">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['user_name'] . ' (' . $row['user_email'] . ')' ?></td>
                        <td><?= $row['supplier_name'] . ' (' . $row['supplier_email'] . ')' ?></td>
                        <td><?= $row['product_name'] ?></td>
                        <td>$<?= number_format($row['requested_price'], 2) ?></td>
                        <td class="status"><?= ucfirst($row['status']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending') { ?>
                                <button class="approve-btn" onclick="updateQuotationStatus(<?= $row['id'] ?>, 'Approved')">✅ Approve</button>
                                <button class="reject-btn" onclick="updateQuotationStatus(<?= $row['id'] ?>, 'Rejected')">❌ Reject</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../frontend/js/admin_quotations.js"></script>
</body>
</html>
