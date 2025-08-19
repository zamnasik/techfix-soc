<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$supplierId = $_SESSION['user_id'];

// Fetch Orders
$query = "SELECT * FROM orders WHERE supplier_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$result = $stmt->get_result();

// Handle Order Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    $updateQuery = "UPDATE orders SET status = ? WHERE id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sii", $newStatus, $orderId, $supplierId);

    if ($stmt->execute()) {
        header("Location: orders.php?success=Order status updated successfully.");
        exit();
    } else {
        $error = "Error updating order status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="orders-container">
    <h2>📦 Order Management</h2>
    <p>View and manage orders from TechFix users.</p>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php if (isset($_GET['success'])) { echo "<p class='success'>" . $_GET['success'] . "</p>"; } ?>

    <!-- Orders Table -->
    <h3>📋 New Orders</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price (LKR)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr class="<?= strtolower($row['status']); ?>">
            <td>#<?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($row['total_price'], 2) ?></td>
            <td class="<?= strtolower($row['status']); ?>">
                <?= ucfirst($row['status']) ?>
            </td>
            <td>
                <!-- Order Status Update -->
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                    <select name="status">
                        <option value="Processing" <?= ($row['status'] == 'Processing') ? 'selected' : '' ?>>Processing</option>
                        <option value="Shipped" <?= ($row['status'] == 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                        <option value="Completed" <?= ($row['status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
                    </select>
                    <button type="submit" name="update_status" class="update-btn">✔ Update</button>
                </form>

                <!-- Generate Invoice -->
                <a href="generate_invoice.php?order_id=<?= $row['id'] ?>" class="invoice-btn">🧾 Generate Invoice</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <button class="back-btn" onclick="window.location.href='profile.php'">🔙 Back to Profile</button>
</div>

</body>
</html>
