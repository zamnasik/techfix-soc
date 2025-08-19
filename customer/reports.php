<?php
require '../backend/db.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Monthly Spending Data
$spendingQuery = "SELECT DATE_FORMAT(payment_date, '%Y-%m') AS month, SUM(amount) AS total_spent
                  FROM payments WHERE user_id = ? AND status = 'Completed'
                  GROUP BY month ORDER BY month DESC";
$spendingStmt = $conn->prepare($spendingQuery);
$spendingStmt->bind_param("i", $user_id);
$spendingStmt->execute();
$spendingResult = $spendingStmt->get_result();
$spendingData = [];
while ($row = $spendingResult->fetch_assoc()) {
    $spendingData[] = $row;
}

// Fetch Supplier Performance Data
// Fetch Supplier Performance Data
$supplierQuery = "SELECT s.company_name, COUNT(o.id) AS total_orders, 
                         AVG(DATEDIFF(p.payment_date, o.created_at)) AS avg_delivery_time 
                  FROM orders o
                  JOIN users s ON o.supplier_id = s.id
                  LEFT JOIN payments p ON o.id = p.order_id
                  WHERE o.user_id = ?
                  GROUP BY s.company_name
                  ORDER BY total_orders DESC";

$supplierStmt = $conn->prepare($supplierQuery);
$supplierStmt->bind_param("i", $user_id);
$supplierStmt->execute();
$supplierResult = $supplierStmt->get_result();
$supplierData = [];
while ($row = $supplierResult->fetch_assoc()) {
    $supplierData[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

<?php include '../frontend/navbar.php'; ?> 

<div class="container">
    <h2>📊 Reports & Analytics</h2>

    <!-- Monthly Spending Chart -->
    <div class="chart-container">
        <h3>💰 Monthly Spending</h3>
        <canvas id="spendingChart"></canvas>
    </div>

    <!-- Supplier Performance Chart -->
    <div class="chart-container">
        <h3>📦 Supplier Performance</h3>
        <canvas id="supplierChart"></canvas>
    </div>

    <!-- Download Reports -->
    <div class="download-buttons">
    <a href="../backend/export_reports_pdf.php?type=pdf" class="btn-download pdf">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>
    <a href="../backend/export_reports_excel.php?type=excel" class="btn-download excel">
        <i class="fas fa-file-excel"></i> Download Excel
    </a>
</div>


<script>
// Monthly Spending Data
const spendingData = <?php echo json_encode($spendingData); ?>;
const spendingLabels = spendingData.map(row => row.month);
const spendingValues = spendingData.map(row => row.total_spent);

// Supplier Performance Data
const supplierData = <?php echo json_encode($supplierData); ?>;
const supplierLabels = supplierData.map(row => row.company_name);
const supplierValues = supplierData.map(row => row.total_orders);

// Render Monthly Spending Chart
const spendingCtx = document.getElementById('spendingChart').getContext('2d');
new Chart(spendingCtx, {
    type: 'bar',
    data: {
        labels: spendingLabels,
        datasets: [{
            label: 'Total Spent (LKR)',
            data: spendingValues,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    }
});

// Render Supplier Performance Chart
const supplierCtx = document.getElementById('supplierChart').getContext('2d');
new Chart(supplierCtx, {
    type: 'bar',
    data: {
        labels: supplierLabels,
        datasets: [{
            label: 'Total Orders',
            data: supplierValues,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    }
});
</script>

</body>
</html>
