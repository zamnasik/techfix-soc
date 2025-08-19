<?php
session_start();
require '../backend/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch payment history
$query = "SELECT payments.*, orders.product_name FROM payments 
          JOIN orders ON payments.order_id = orders.id 
          WHERE payments.user_id = ?";
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
    <title>Payments & Invoices | TechFix</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<?php include '../frontend/navbar.php'; ?> 

<div class="container">
    <h2>Your Payments & Invoices</h2>
    <a href="profile.php" class="back-btn">⬅ Back to Profile</a>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['product_name']; ?></td>
                    <td>$<?php echo number_format($row['amount'], 2); ?></td>
                    <td class="<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                    <td><?php echo $row['payment_date']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <button class="pay-btn" onclick="openPaymentModal(<?php echo $row['id']; ?>)">Pay Now</button>
                        <?php } else { ?>
                            <a href="../backend/download_invoice.php?payment_id=<?php echo $row['id']; ?>" class="invoice-btn">Download Invoice</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePaymentModal()">&times;</span>
        <h2>Make a Payment</h2>
        <form id="paymentForm">
            <input type="hidden" id="payment_id" name="payment_id">
            <label>Select Payment Method:</label>
            <select name="payment_method" id="payment_method">
                <option value="bank">Bank Transfer</option>
                <option value="credit_card">Credit Card</option>
            </select>
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</div>

<script src="../frontend/js/payments.js"></script>
<?php include '../frontend/footer.php'; ?>
</body>
</html>
