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

// Delete query
$deleteQuery = "DELETE FROM inventory WHERE id = ? AND supplier_id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("ii", $inventoryId, $_SESSION['user_id']);

if ($stmt->execute()) {
    header("Location: inventory.php?success=Inventory deleted successfully.");
    exit();
} else {
    header("Location: inventory.php?error=Failed to delete inventory.");
    exit();
}
?>

