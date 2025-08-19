<?php
session_start();
require '../backend/db.php';

// Fetch all inventory products
$query = "SELECT i.id, i.product_name, i.stock, i.price, i.status, u.first_name AS supplier_name 
          FROM inventory i 
          JOIN users u ON i.supplier_id = u.id
          WHERE i.status = 'Approved'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Inventory | TechFix</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<?php include '../frontend/navbar.php'; ?>  <!-- Include Navigation Bar -->

<div class="inventory-container">
    <h2 class="inventory-title">Browse & Compare Supplier Inventory</h2>

    <div class="filter-section">
        <input type="text" id="searchBox" placeholder="Search product..." onkeyup="filterProducts()">
        <select id="sortOptions" onchange="sortProducts()">
            <option value="default">Sort by</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
        </select>
    </div>

    <div class="inventory-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="inventory-card">
                <h3><?= htmlspecialchars($row['product_name']); ?></h3>
                <p>Supplier: <?= htmlspecialchars($row['supplier_name']); ?></p>
                <p>Stock: <?= ($row['stock'] > 0) ? $row['stock'] . " Available" : "<span class='out-of-stock'>Out of Stock</span>"; ?></p>
                <p>Price: <strong>$<?= number_format($row['price'], 2); ?></strong></p>
                <button class="quote-btn" onclick="requestQuotation(<?= $row['id']; ?>)">Request Quotation</button>
                <td><a href="request_quotation.php?id=<?= $row['id'] ?>&name=<?= urlencode($row['product_name']) ?>" class="btn">Request Quotation</a></td>

            </div>
        <?php } ?>
    </div>

    

    <button class="back-btn" onclick="window.history.back();">← Back</button>
</div>

<script src="../frontend/js/inventory.js"></script>

<?php include '../frontend/footer.php'; ?>  <!-- Include Footer -->
</body>
</html>
