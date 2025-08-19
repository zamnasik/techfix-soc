<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle Adding New Stock
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $insertQuery = "INSERT INTO inventory (supplier_id, product_name, stock, price, status) VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isid", $userId, $productName, $quantity, $price);

    if ($stmt->execute()) {
        header("Location: inventory.php?success=Stock added successfully.");
        exit();
    } else {
        $error = "Error adding stock.";
    }
}

// Fetch Inventory List
$query = "SELECT * FROM inventory WHERE supplier_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="inventory-container">
    <h2>📦 Inventory Management</h2>
    <p>Manage your stock, update quantities, and view approval status.</p>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php if (isset($_GET['success'])) { echo "<p class='success'>" . $_GET['success'] . "</p>"; } ?>

    <!-- Add New Stock Form -->
    <div class="add-stock-form">
        <h3>Add New Stock</h3>
        <form method="POST">
            <label>Product Name:</label>
            <input type="text" name="product_name" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" required>

            <label>Price (LKR):</label>
            <input type="number" name="price" step="0.01" required>

            <button type="submit" class="add-btn">➕ Add Stock</button>
        </form>
    </div>

    <!-- Inventory List -->
    <h3>📋 Your Inventory</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price (LKR)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr class="<?= ($row['stock'] < 5) ? 'low-stock' : ''; ?>">
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= $row['stock'] ?></td>
            <td><?= number_format($row['price'], 2) ?></td>
            <td class="<?= strtolower($row['status']); ?>">
                <?= ucfirst($row['status']) ?>
            </td>
            <td>
                <a href="edit_inventory.php?id=<?= $row['id'] ?>" class="edit-btn">✏ Edit</a>
                <a href="delete_inventory.php?id=<?= $row['id'] ?>" class="delete-btn">🗑 Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<button class="back-btn" onclick="window.location.href='profile.php'">🔙 Back to Profile</button>


</body>
</html>
