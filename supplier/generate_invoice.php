<?php
require '../backend/db.php';
require '../vendor/dompdf/autoload.inc.php'; // Ensure you have installed dompdf via Composer

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    exit("Order ID is required.");
}

$orderId = $_GET['order_id'];

// Fetch order details
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Generate PDF Invoice
$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);
$html = "<h1>Invoice</h1>
         <p>Order ID: #{$order['id']}</p>
         <p>Product: {$order['product_name']}</p>
         <p>Quantity: {$order['quantity']}</p>
         <p>Total Price: LKR " . number_format($order['total_price'], 2) . "</p>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("invoice_{$order['id']}.pdf", ["Attachment" => true]);
?>
