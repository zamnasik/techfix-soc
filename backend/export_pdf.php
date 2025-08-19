<?php
require '../vendor/dompdf/autoload.inc.php';  // Load Dompdf
require 'db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isHtml5ParserEnabled', true);  // Enable HTML5 parsing

$dompdf = new Dompdf($options);

// Start output buffering to avoid any extra PHP warnings/errors being included in the PDF
ob_start();

// Generate HTML content for PDF
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Order Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Supplier</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT orders.*, 
                             users.first_name AS user_name, users.email AS user_email,
                             suppliers.first_name AS supplier_name, suppliers.email AS supplier_email
                      FROM orders
                      JOIN users ON orders.user_id = users.id
                      JOIN users AS suppliers ON orders.supplier_id = suppliers.id";

            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) . " (" . htmlspecialchars($row['user_email']) . ")" ?></td>
                    <td><?= htmlspecialchars($row['supplier_name']) . " (" . htmlspecialchars($row['supplier_email']) . ")" ?></td>
                    <td>$<?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

<?php
$html = ob_get_clean(); // Get the buffered content
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Order_Report.pdf");
?>
