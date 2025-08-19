<?php
require '../vendor/dompdf/autoload.inc.php';
require 'db.php';
session_start();

use Dompdf\Dompdf;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM payments WHERE user_id = ? AND status = 'Completed'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$html = '<h2>Customer Payment Report</h2>';
$html .= '<table border="1" width="100%">
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
            </tr>';
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['amount'] . '</td>
                <td>' . $row['status'] . '</td>
                <td>' . $row['payment_date'] . '</td>
              </tr>';
}
$html .= '</table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Customer_Report.pdf", ["Attachment" => 1]);
?>
