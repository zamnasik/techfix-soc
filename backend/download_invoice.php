<?php
require 'db.php';
require '../vendor/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch payment details
    $query = "SELECT p.*, o.product_name FROM payments p
    JOIN orders o ON p.order_id = o.id 
    WHERE p.user_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();

    if ($payment) {
        $html = "
        <h2>Invoice</h2>
        <p>Product: {$payment['product_name']}</p>
        <p>Amount: $ {$payment['amount']}</p>
        <p>Status: {$payment['status']}</p>
        <p>Date: {$payment['payment_date']}</p>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Invoice_{$payment_id}.pdf");
    }
}
?>
