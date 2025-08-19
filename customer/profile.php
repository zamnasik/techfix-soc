<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../frontend/login.php");
    exit();
}

$customerId = $_SESSION['user_id'];

// Fetch Customer Details
$query = "SELECT first_name, last_name, email, phone, status FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Set Account Status
$accountStatus = ($customer['status'] == 'Active') ? "✅ Active" : "⛔ Suspended";
$statusClass = ($customer['status'] == 'Active') ? "status-active" : "status-suspended";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="customer-profile">
    <div class="profile-header">
        <h2>👤 Welcome, <?= htmlspecialchars($customer['first_name'] . " " . $customer['last_name']) ?>!</h2>
        <p>Email: <?= htmlspecialchars($customer['email']) ?></p>
        <p>📞 Contact: <?= htmlspecialchars($customer['phone']) ?></p>
        <p class="<?= $statusClass ?>">Account Status: <?= $accountStatus ?></p>
        <a href="edit_profile.php" class="edit-profile-btn">✏ Edit Profile</a>
        <a href="../backend/logout.php" class="logout-btn">🚪 Logout</a>
    </div>

    <!-- Cards Section -->
    <div class="customer-cards">
        <a href="browse_inventory.php" class="card">
            <h3>🛒 Browse & Compare Inventory</h3>
            <p>View and compare supplier products.</p>
        </a>

        <a href="request_quotation.php" class="card">
            <h3>💬 Request Quotations</h3>
            <p>Request pricing quotes from suppliers.</p>
        </a>

        <a href="place_order.php" class="card">
            <h3>📦 Order Tracking</h3>
            <p>Track your orders and delivery status.</p>
        </a>

        <a href="payments.php" class="card">
            <h3>💳 Payments & Invoices</h3>
            <p>View payment history and invoices.</p>
        </a>

        <a href="support.php" class="card">
            <h3>🆘 Support & Help Desk</h3>
            <p>Get assistance and customer support.</p>
        </a>

        <a href="reports.php" class="card">
            <h3>📊 Reports & Analytics</h3>
            <p>View your order and purchase statistics.</p>
        </a>
    </div>
</div>

</body>
</html>
