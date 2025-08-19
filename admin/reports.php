<?php
include '../backend/db.php';
session_start();

// Check if Admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch total orders
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'];

// Fetch total revenue
// Fetch total revenue (only for completed orders)
$totalRevenueQuery = "SELECT SUM(orders.total_amount) AS total_revenue 
                      FROM orders 
                      WHERE orders.status = 'Completed'";
$totalRevenueResult = $conn->query($totalRevenueQuery);
$totalRevenue = $totalRevenueResult->fetch_assoc()['total_revenue'];

// Fetch total revenue (only for completed orders)
$totalRevenueQuery = "SELECT SUM(orders.total_amount) AS total_revenue 
                      FROM orders 
                      WHERE orders.status = 'Completed'";
$totalRevenueResult = $conn->query($totalRevenueQuery);
$totalRevenue = $totalRevenueResult->fetch_assoc()['total_revenue'];

// Fetch top-performing suppliers
$topSuppliersQuery = "SELECT orders.supplier_id, users.first_name, users.last_name, COUNT(*) AS orders_count
                      FROM orders 
                      JOIN users ON orders.supplier_id = users.id
                      WHERE orders.status = 'Completed' 
                      GROUP BY orders.supplier_id 
                      ORDER BY orders_count DESC 
                      LIMIT 5";
$topSuppliersResult = $conn->query($topSuppliersQuery);
$topSuppliers = [];
while ($row = $topSuppliersResult->fetch_assoc()) {
    $topSuppliers[] = $row;
}


// Fetch monthly order trends
$monthlyOrdersQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS order_count 
                       FROM orders GROUP BY month ORDER BY month ASC";
$monthlyOrdersResult = $conn->query($monthlyOrdersQuery);
$monthlyOrders = [];
while ($row = $monthlyOrdersResult->fetch_assoc()) {
    $monthlyOrders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reports & Analytics</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h2>Admin Panel - Reports & Analytics</h2>
        <button class="logout-btn" onclick="window.location.href='../backend/logout.php'">🚪 Logout</button>
    </div>

    <!-- Dashboard Widgets -->
    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Orders</h3>
            <p><?= $totalOrders ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Revenue</h3>
            <p>$<?= number_format($totalRevenue, 2) ?></p>
        </div>
    </div>

    <!-- Monthly Order Trends Chart -->
    <h3>Monthly Order Trends</h3>
    <canvas id="ordersChart"></canvas>

    <!-- Top Performing Suppliers -->
    <h3>Top Suppliers</h3>
    <table>
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Total Orders</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topSuppliers as $supplier) { ?>
                <tr>
                    <td><?= $supplier['first_name'] . ' ' . $supplier['last_name'] ?></td>
                    <td><?= $supplier['orders_count'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Report Download Section -->
    <h3>Download Reports</h3>
    <button onclick="downloadPDF()">📥 Download PDF</button>
    <button onclick="downloadExcel()">📊 Download Excel</button>
</div>

<script>
    let months = <?= json_encode(array_column($monthlyOrders, 'month')) ?>;
    let orderCounts = <?= json_encode(array_column($monthlyOrders, 'order_count')) ?>;

    let ctx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Orders per Month',
                data: orderCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        }
    });

    function downloadPDF() {
        window.location.href = '../backend/export_pdf.php';
    }

    function downloadExcel() {
        window.location.href = '../backend/export_excel.php';
    }
</script>

</body>
</html>
