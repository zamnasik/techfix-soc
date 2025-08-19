<?php
require '../vendor/dompdf/autoload.inc.php';
require 'db.php';
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Amount');
$sheet->setCellValue('C1', 'Status');
$sheet->setCellValue('D1', 'Payment Date');

$rowNumber = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, $row['id']);
    $sheet->setCellValue('B' . $rowNumber, $row['amount']);
    $sheet->setCellValue('C' . $rowNumber, $row['status']);
    $sheet->setCellValue('D' . $rowNumber, $row['payment_date']);
    $rowNumber++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'Customer_Report.xlsx';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$writer->save('php://output');
?>
