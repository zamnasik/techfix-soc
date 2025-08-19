<?php
include '../backend/db.php';
session_start();

// Check if User is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'TechFix User') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch all suppliers
$query = "SELECT id, first_name, last_name, email FROM users WHERE role = 'Supplier'";
$suppliers = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Quotation</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Request a Quotation</h2>
    
    <!-- Quotation Request Form -->
    <form id="quotationForm">
        <label for="product">Product Name:</label>
        <input type="text" id="product" name="product" required>

        <label for="requested_price">Your Expected Price:</label>
        <input type="number" id="requested_price" name="requested_price" required>

        <label for="supplier">Select Supplier:</label>
        <select id="supplier" name="supplier">
            <?php while ($supplier = $suppliers->fetch_assoc()) { ?>
                <option value="<?= $supplier['id'] ?>">
                    <?= $supplier['first_name'] . ' ' . $supplier['last_name'] . ' (' . $supplier['email'] . ')' ?>
                </option>
            <?php } ?>
        </select>

        <button type="button" onclick="submitQuotation()">📩 Submit Request</button>
    </form>
</div>

<script src="../frontend/js/quotation.js"></script>
</body>
</html>
