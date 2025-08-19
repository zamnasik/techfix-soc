<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$query = "SELECT company_name, address, description, logo, status FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$supplier = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier Profile</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="profile-container">
    <!-- Supplier Profile Header -->
    <div class="profile-header">
        <div class="logo-container">
            <img src="<?= $supplier['logo'] ? '../uploads/' . $supplier['logo'] : '../assets/default-logo.png' ?>" alt="Company Logo">
        </div>
        <div class="profile-info">
            <h2><?= htmlspecialchars($supplier['company_name']) ?></h2>
            <p class="status-label <?= strtolower($supplier['status']) ?>">
                Account Status: <?= ucfirst($supplier['status']) ?>
            </p>
            <button onclick="window.location.href='setup_profile.php'">✏ Edit Profile</button>
            <button class="logout-btn" onclick="window.location.href='../backend/logout.php'">🚪 Logout</button>
        </div>
    </div>

    <!-- Supplier Details Section -->
    <div class="profile-details">
        <p><strong>Address:</strong> <?= htmlspecialchars($supplier['address']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($supplier['description']) ?></p>
    </div>

    <!-- Supplier Dashboard Cards -->
    <div class="dashboard-cards">
        <div class="card" onclick="window.location.href='inventory.php'">
            <img src="../assets/inventory.png" alt="Inventory">
            <h3>Inventory Management</h3>
        </div>
        <div class="card" onclick="window.location.href='quotations.php'">
            <img src="../assets/quotations.png" alt="Quotations">
            <h3>Manage Quotations</h3>
        </div>
        <div class="card" onclick="window.location.href='orders.php'">
            <img src="../assets/orders.png" alt="Orders">
            <h3>Order Management</h3>
        </div>
        <div class="card" onclick="window.location.href='payments.php'">
            <img src="../assets/payments.png" alt="Payments">
            <h3>Payments & Transactions</h3>
        </div>
        <div class="card" onclick="window.location.href='reports.php'">
            <img src="../assets/reports.png" alt="Reports">
            <h3>Reports & Analytics</h3>
        </div>
    </div>
</div>

</body>
</html>
