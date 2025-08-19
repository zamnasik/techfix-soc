<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $query = "DELETE FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => "Stock deleted."]);
    } else {
        echo json_encode(["error" => "Failed to delete stock."]);
    }
}
?>
