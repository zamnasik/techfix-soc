<?php
session_start();
require '../backend/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders
$query = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking | TechFix</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<?php include '../frontend/navbar.php'; ?> 

<div class="container">
    <h2>Your Orders</h2>
    <a href="profile.php" class="back-btn">⬅ Back to Profile</a>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Cancel</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>$<?php echo $row['total_price']; ?></td>
                    <td class="<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Processing') { ?>
                            <button onclick="cancelOrder(<?php echo $row['id']; ?>)">Cancel</button>
                        <?php } else { echo "N/A"; } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm("Are you sure you want to cancel this order?")) {
        window.location.href = "../backend/cancel_order.php?id=" + orderId;
    }
}
</script>

<?php include '../frontend/footer.php'; ?> 
</body>
</html>
