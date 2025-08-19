<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName = $_POST['company_name'];
    $address = $_POST['address'];
    $description = $_POST['description'];

    if (!empty($_FILES['logo']['name'])) {
        $uploadDir = '../uploads/';
        
        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['logo']['name']); // Unique filename
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath);
        }
    } else {
        $fileName = NULL;
    }

    $updateQuery = "UPDATE users SET company_name=?, address=?, description=?, logo=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $companyName, $address, $description, $fileName, $userId);

    if ($stmt->execute()) {
        header("Location: profile.php"); // Redirect to profile page
        exit();
    } else {
        $error = "Failed to save profile details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup Supplier Profile</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="setup-container">
    <div class="setup-box">
        <h2>Complete Your Supplier Profile</h2>
        <p>Fill in your details to continue using the platform.</p>

        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Company Name:</label>
                <input type="text" name="company_name" required>
            </div>

            <div class="input-group">
                <label>Business Address:</label>
                <textarea name="address" required></textarea>
            </div>

            <div class="input-group">
                <label>Company Description:</label>
                <textarea name="description" required></textarea>
            </div>

            <div class="input-group">
                <label>Upload Company Logo:</label>
                <input type="file" name="logo" accept="image/*">
            </div>

            <button type="submit" class="save-btn">💾 Save Profile</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='profile.php'">❌ Cancel</button>
        </form>
    </div>
</div>

</body>
</html>
