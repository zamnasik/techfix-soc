<?php
session_start();
require '../backend/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch approved products from inventory
$query = "SELECT * FROM inventory WHERE status = 'Approved' AND stock > 0";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order | TechFix</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<?php include '../frontend/navbar.php'; ?> 

<div class="container">
    <h2>Place Your Order</h2>
    <a href="profile.php" class="back-btn">⬅ Back to Profile</a>
    <a href="order_tracking.php" class="back-btn">⬅ Order Tracking</a>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Supplier</th>
                <th>Available Stock</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['supplier_id']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>$<?php echo $row['price']; ?></td>
                    <td>
                        <button class="order-btn" onclick="openOrderModal(
                            '<?php echo $row['id']; ?>',
                            '<?php echo $row['product_name']; ?>',
                            '<?php echo $row['supplier_id']; ?>',
                            '<?php echo $row['price']; ?>'
                        )">Order Now</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Order Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeOrderModal()">&times;</span>
        <h2>Confirm Your Order</h2>
        <form id="orderForm">
            <input type="hidden" id="product_id" name="product_id">
            <input type="hidden" id="supplier_id" name="supplier_id">
            <label>Product Name:</label>
            <input type="text" id="product_name" name="product_name" readonly>
            <label>Price ($):</label>
            <input type="text" id="price" name="price" readonly>
            <label>Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" required>
            <button type="submit">Place Order</button>
        </form>
    </div>
</div>

<script src="../frontend/js/order.js"></script>
<?php include '../frontend/footer.php'; ?> 
</body>
</html>
