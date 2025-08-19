<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: inventory.php");
    exit();
}

$inventoryId = $_GET['id'];

// Fetch inventory details
$query = "SELECT product_name, stock, price FROM inventory WHERE id = ? AND supplier_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $inventoryId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: inventory.php");
    exit();
}

$inventory = $result->fetch_assoc();

// Handle Inventory Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $updateQuery = "UPDATE inventory SET stock = ?, price = ? WHERE id = ? AND supplier_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("diii", $quantity, $price, $inventoryId, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: inventory.php?success=Inventory updated successfully.");
        exit();
    } else {
        $error = "Error updating inventory.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Inventory</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="edit-inventory-container">
    <h2>✏ Edit Inventory</h2>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

    <form method="POST">
        <label>Product Name:</label>
        <input type="text" value="<?= htmlspecialchars($inventory['product_name']) ?>" disabled>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?= $inventory['stock'] ?>" required>

        <label>Price (LKR):</label>
        <input type="number" name="price" value="<?= $inventory['price'] ?>" step="0.01" required>

        <button type="submit" class="update-btn">💾 Update</button>
        <button type="button" class="cancel-btn" onclick="window.location.href='inventory.php'">🔙 Back</button>
    </form>
</div>

</body>
</html>
