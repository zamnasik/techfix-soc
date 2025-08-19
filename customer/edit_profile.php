<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Customer') {
    header("Location: ../frontend/login.php");
    exit();
}

$customerId = $_SESSION['user_id'];

// Fetch Customer Details
$query = "SELECT first_name, last_name, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];

    $updateQuery = "UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $firstName, $lastName, $phone, $customerId);

    if ($stmt->execute()) {
        header("Location: profile.php?success=Profile updated successfully.");
        exit();
    } else {
        $error = "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="edit-profile-container">
    <h2>✏ Edit Profile</h2>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST">
        <input type="text" name="first_name" value="<?= htmlspecialchars($customer['first_name']) ?>" required>
        <input type="text" name="last_name" value="<?= htmlspecialchars($customer['last_name']) ?>" required>
        <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>
        <button type="submit">Update Profile</button>
    </form>
    <a href="profile.php" class="back-btn">🔙 Back to Profile</a>
</div>

</body>
</html>
