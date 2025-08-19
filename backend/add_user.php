<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $status = 'Active';

    // Check if email already exists
    $checkQuery = "SELECT id FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Email already exists."]);
        exit();
    }

    // Insert the new user
    $query = "INSERT INTO users (first_name, last_name, email, password, phone, role, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $password, $phone, $role, $status);

    if ($stmt->execute()) {
        echo json_encode(["success" => "User added successfully."]);
    } else {
        echo json_encode(["error" => "Failed to add user."]);
    }
}
?>
