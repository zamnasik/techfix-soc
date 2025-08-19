<?php
$host = "localhost";
$user = "root";  // Default for XAMPP
$password = "";  // Leave empty for XAMPP
$database = "techfix_db"; // Your database name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
