<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$supplierId = $_SESSION['user_id'];

// Fetch Monthly Sales Data
$salesQuery = "SELECT MONTH(payment_date) AS month, SUM(amount) AS total_sales FROM payments 
               WHERE supplier_id = ? AND status = 'Completed' 
               GROUP BY MONTH(payment_date)";
$stmt = $conn->prepare($salesQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$salesResult = $stmt->get_result();
$monthlySales = [];
while ($row = $salesResult->fetch_assoc()) {
    $monthlySales[$row['month']] = $row['total_sales'];
}

// Fetch Order Fulfillment Data
$orderQuery = "SELECT status, COUNT(*) AS count FROM orders WHERE supplier_id = ? GROUP BY status";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$orderResult = $stmt->get_result();
$orderData = [];
while ($row = $orderResult->fetch_assoc()) {
    $orderData[$row['status']] = $row['count'];
}

// Fetch Quotation Approval Data
$quoteQuery = "SELECT status, COUNT(*) AS count FROM quotations WHERE supplier_id = ? GROUP BY status";
$stmt = $conn->prepare($quoteQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$quoteResult = $stmt->get_result();
$quotationData = [];
while ($row = $quoteResult->fetch_assoc()) {
    $quotationData[$row['status']] = $row['count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports & Analytics</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="reports-container">
    <h2>📊 Reports & Analytics</h2>
    <p>Analyze sales performance, order fulfillment, and quotation approvals.</p>

    <div class="charts">
        <!-- Sales Overview Chart -->
        <canvas id="salesChart"></canvas>

        <!-- Order Fulfillment Chart -->
        <canvas id="orderChart"></canvas>

        <!-- Quotation Approval Chart -->
        <canvas id="quotationChart"></canvas>
    </div>

    <!-- Download Reports Buttons -->
    <div class="report-buttons">
        <a href="export_pdf.php" class="pdf-btn">📄 Download PDF Report</a>
        <a href="export_excel.php" class="excel-btn">📊 Download Excel Report</a>
    </div>

    <button class="back-btn" onclick="window.location.href='profile.php'">🔙 Back to Profile</button>
</div>

<script>
    // Sales Data
    const salesData = {
        labels: <?= json_encode(array_keys($monthlySales)) ?>,
        datasets: [{
            label: 'Monthly Sales (LKR)',
            data: <?= json_encode(array_values($monthlySales)) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    // Orders Data
    const orderData = {
        labels: <?= json_encode(array_keys($orderData)) ?>,
        datasets: [{
            label: 'Order Fulfillment Rate',
            data: <?= json_encode(array_values($orderData)) ?>,
            backgroundColor: ['rgba(75, 192, 192, 0.5)', 'rgba(255, 159, 64, 0.5)', 'rgba(255, 99, 132, 0.5)'],
            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 99, 132, 1)'],
            borderWidth: 1
        }]
    };

    // Quotation Approval Data
    const quotationData = {
        labels: <?= json_encode(array_keys($quotationData)) ?>,
        datasets: [{
            label: 'Quotation Approval Rate',
            data: <?= json_encode(array_values($quotationData)) ?>,
            backgroundColor: ['rgba(153, 102, 255, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(201, 203, 207, 0.5)'],
            borderColor: ['rgba(153, 102, 255, 1)', 'rgba(255, 206, 86, 1)', 'rgba(201, 203, 207, 1)'],
            borderWidth: 1
        }]
    };

    // Render Charts
    new Chart(document.getElementById('salesChart'), { type: 'bar', data: salesData });
    new Chart(document.getElementById('orderChart'), { type: 'doughnut', data: orderData });
    new Chart(document.getElementById('quotationChart'), { type: 'pie', data: quotationData });
</script>

</body>
</html>
