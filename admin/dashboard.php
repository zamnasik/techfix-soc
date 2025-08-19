<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Ensure session variables exist
$adminName = isset($_SESSION['first_name']) && isset($_SESSION['last_name']) 
    ? $_SESSION['first_name'] . " " . $_SESSION['last_name'] 
    : "Admin";  // Default to "Admin" if session variables are missing
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="admin-dashboard">
    <div class="admin-header">
        <h2>👋 Welcome, <?= htmlspecialchars($adminName) ?>!</h2>
        <p>Manage the TechFix system efficiently.</p>
        <a href="../backend/logout.php" class="logout-btn">🚪 Logout</a>
    </div>

    <!-- Cards Section -->
    <div class="admin-cards">
        <a href="users.php" class="card">
            <h3>👤 User Management</h3>
            <p>Add, remove, and edit users.</p>
        </a>

        <a href="inventory.php" class="card">
            <h3>📦 Supplier & Inventory</h3>
            <p>Approve suppliers and manage stock.</p>
        </a>

        <a href="orders.php" class="card">
            <h3>📑 Order Management</h3>
            <p>Track orders and approvals.</p>
        </a>

        <a href="quotations.php" class="card">
            <h3>💰 Quotations & Pricing</h3>
            <p>Compare supplier quotes.</p>
        </a>

        <a href="reports.php" class="card">
            <h3>📊 Reports & Analytics</h3>
            <p>View order stats and supplier performance.</p>
        </a>
    </div>
</div>

</body>
</html>
