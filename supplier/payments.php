<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$supplierId = $_SESSION['user_id'];

// Fetch Payments
$query = "SELECT * FROM payments WHERE supplier_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$result = $stmt->get_result();

// Handle Payment Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_payment'])) {
    $paymentId = $_POST['payment_id'];

    $updateQuery = "UPDATE payments SET status = 'Requested' WHERE id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $paymentId, $supplierId);

    if ($stmt->execute()) {
        header("Location: payments.php?success=Payment request sent successfully.");
        exit();
    } else {
        $error = "Error requesting payment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments & Transactions</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="payments-container">
    <h2>💰 Payments & Transactions</h2>
    <p>View completed payments and track pending transactions.</p>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php if (isset($_GET['success'])) { echo "<p class='success'>" . $_GET['success'] . "</p>"; } ?>

    <!-- Payments Table -->
    <h3>📋 Payment Summary</h3>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>Order ID</th>
            <th>Amount (LKR)</th>
            <th>Status</th>
            <th>Payment Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr class="<?= strtolower($row['status']); ?>">
            <td>#<?= $row['id'] ?></td>
            <td>#<?= $row['order_id'] ?></td>
            <td><?= number_format($row['amount'], 2) ?></td>
            <td class="<?= strtolower($row['status']); ?>">
                <?= ucfirst($row['status']) ?>
            </td>
            <td><?= $row['payment_date'] ?: 'N/A' ?></td>
            <td>
                <?php if ($row['status'] === 'Pending') { ?>
                <form method="POST">
                    <input type="hidden" name="payment_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="request_payment" class="request-btn">📩 Request Payment</button>
                </form>
                <?php } else { ?>
                <span class="completed">✅ Paid</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <button class="back-btn" onclick="window.location.href='profile.php'">🔙 Back to Profile</button>
</div>

</body>
</html>
