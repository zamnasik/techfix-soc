<?php
require '../backend/db.php';
require '../vendor/dompdf/autoload.inc.php'; // Ensure dompdf is installed

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$supplierId = $_SESSION['user_id'];

// Fetch Sales Data
$salesQuery = "SELECT MONTH(payment_date) AS month, SUM(amount) AS total_sales FROM payments 
               WHERE supplier_id = ? AND status = 'Completed' GROUP BY MONTH(payment_date)";
$stmt = $conn->prepare($salesQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$salesResult = $stmt->get_result();
$salesData = "";
while ($row = $salesResult->fetch_assoc()) {
    $salesData .= "<tr><td>" . $row['month'] . "</td><td>LKR " . number_format($row['total_sales'], 2) . "</td></tr>";
}

// Fetch Order Data
$orderQuery = "SELECT status, COUNT(*) AS count FROM orders WHERE supplier_id = ? GROUP BY status";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$orderResult = $stmt->get_result();
$orderData = "";
while ($row = $orderResult->fetch_assoc()) {
    $orderData .= "<tr><td>" . ucfirst($row['status']) . "</td><td>" . $row['count'] . "</td></tr>";
}

// Fetch Quotation Data
$quoteQuery = "SELECT status, COUNT(*) AS count FROM quotations WHERE supplier_id = ? GROUP BY status";
$stmt = $conn->prepare($quoteQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$quoteResult = $stmt->get_result();
$quotationData = "";
while ($row = $quoteResult->fetch_assoc()) {
    $quotationData .= "<tr><td>" . ucfirst($row['status']) . "</td><td>" . $row['count'] . "</td></tr>";
}

// Generate PDF
$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);
$html = "<h2>Supplier Report</h2>
         <h3>Monthly Sales</h3>
         <table border='1' cellpadding='5' cellspacing='0'><tr><th>Month</th><th>Total Sales</th></tr>$salesData</table>
         <h3>Order Fulfillment</h3>
         <table border='1' cellpadding='5' cellspacing='0'><tr><th>Status</th><th>Count</th></tr>$orderData</table>
         <h3>Quotation Approval</h3>
         <table border='1' cellpadding='5' cellspacing='0'><tr><th>Status</th><th>Count</th></tr>$quotationData</table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Supplier_Report.pdf", ["Attachment" => true]);
?>
