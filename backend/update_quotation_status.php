<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Ensure the status is either Approved or Rejected
    if ($status !== 'Approved' && $status !== 'Rejected') {
        echo json_encode(["error" => "Invalid status update."]);
        exit();
    }

    $query = "UPDATE quotations SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Quotation status updated successfully!"]);
    } else {
        echo json_encode(["error" => "Failed to update quotation status."]);
    }
}
?>
