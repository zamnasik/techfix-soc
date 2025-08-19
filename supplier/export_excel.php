<?php
require '../backend/db.php';
require '../vendor/dompdf/autoload.inc.php'; // Ensure PhpSpreadsheet is installed

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$supplierId = $_SESSION['user_id'];
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header Row
$sheet->setCellValue('A1', 'Report Type');
$sheet->setCellValue('B1', 'Value');

// Fetch Sales Data
$salesQuery = "SELECT MONTH(payment_date) AS month, SUM(amount) AS total_sales FROM payments 
               WHERE supplier_id = ? AND status = 'Completed' GROUP BY MONTH(payment_date)";
$stmt = $conn->prepare($salesQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$salesResult = $stmt->get_result();
$rowNum = 2;
while ($row = $salesResult->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, "Sales in Month " . $row['month']);
    $sheet->setCellValue('B' . $rowNum, "LKR " . number_format($row['total_sales'], 2));
    $rowNum++;
}

// Fetch Order Data
$orderQuery = "SELECT status, COUNT(*) AS count FROM orders WHERE supplier_id = ? GROUP BY status";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$orderResult = $stmt->get_result();
while ($row = $orderResult->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, "Orders " . ucfirst($row['status']));
    $sheet->setCellValue('B' . $rowNum, $row['count']);
    $rowNum++;
}

// Fetch Quotation Data
$quoteQuery = "SELECT status, COUNT(*) AS count FROM quotations WHERE supplier_id = ? GROUP BY status";
$stmt = $conn->prepare($quoteQuery);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$quoteResult = $stmt->get_result();
while ($row = $quoteResult->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, "Quotations " . ucfirst($row['status']));
    $sheet->setCellValue('B' . $rowNum, $row['count']);
    $rowNum++;
}

// Download Excel File
$writer = new Xlsx($spreadsheet);
$filename = 'Supplier_Report.xlsx';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>
