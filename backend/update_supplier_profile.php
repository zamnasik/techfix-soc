<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $companyName = $_POST['company_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // File upload handling
    if (!empty($_FILES['logo']['name'])) {
        $uploadDir = '../uploads/';
        $fileName = basename($_FILES['logo']['name']);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                $updateQuery = "UPDATE users SET company_name=?, phone=?, address=?, logo=? WHERE id=?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ssssi", $companyName, $phone, $address, $fileName, $userId);
            }
        }
    } else {
        $updateQuery = "UPDATE users SET company_name=?, phone=?, address=? WHERE id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $companyName, $phone, $address, $userId);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => "Profile updated successfully."]);
    } else {
        echo json_encode(["error" => "Failed to update profile."]);
    }
}
?>
