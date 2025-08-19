<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Fetch user ID and role
    $query = "SELECT id, role FROM users WHERE token = ? AND verified = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];

        // Update user as verified
        $updateQuery = "UPDATE users SET verified = 1 WHERE token = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $token);
        $updateStmt->execute();

        // Redirect to login page after verification
        header("Location: ../frontend/login.php?verified=success");
        exit();
    } else {
        echo "<h2>Invalid or Expired Token</h2>
              <p>Please try registering again or contact support.</p>";
    }
} else {
    echo "<h2>No Token Provided</h2>";
}
?>
