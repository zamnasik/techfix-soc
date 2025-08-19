<?php
session_start();
require '../backend/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch products from inventory
$query = "SELECT * FROM inventory WHERE status = 'Approved'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Quotation | TechFix</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<?php include '../frontend/navbar.php'; ?>

<div class="container">
    <h2>Request a Quotation</h2>
    <a href="profile.php" class="back-btn">⬅ Back to Profile</a>
    <a href="quotation_history.php" class="back-btn">Quotation_History</a>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Supplier</th>
                <th>Stock</th>
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
                        <button class="quote-btn" onclick="openQuotationModal(
                            '<?php echo $row['id']; ?>',
                            '<?php echo $row['product_name']; ?>',
                            '<?php echo $row['supplier_id']; ?>'
                        )">Request Quotation</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Quotation Modal -->
<div id="quotationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeQuotationModal()">&times;</span>
        <h2>Request Quotation</h2>
        <form id="quotationForm">
            <input type="hidden" id="product_id" name="product_id">
            <input type="hidden" id="supplier_id" name="supplier_id">
            <label>Product Name:</label>
            <input type="text" id="product_name" name="product_name" readonly>
            <label>Requested Price ($):</label>
            <input type="number" id="requested_price" name="requested_price" required>
            <button type="submit">Submit Request</button>
        </form>
    </div>
</div>

<script src="../frontend/js/quotation.js"></script>
<?php include '../frontend/footer.php'; ?> 
</body>
</html>
