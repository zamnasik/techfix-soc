<?php
include '../backend/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: ../frontend/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle Price Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quotationId = $_POST['quotation_id'];
    $proposedPrice = $_POST['proposed_price'];

    $updateQuery = "UPDATE quotations SET supplier_id = ?, proposed_price = ?, status = 'Pending' WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("idi", $userId, $proposedPrice, $quotationId);

    if ($stmt->execute()) {
        header("Location: quotations.php?success=Quotation submitted successfully.");
        exit();
    } else {
        $error = "Error submitting quotation.";
    }
}

// Fetch Quotation Requests
$query = "SELECT * FROM quotations WHERE status = 'Requested' OR (supplier_id = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Quotations</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<div class="quotations-container">
    <h2>📜 Manage Quotation Requests</h2>
    <p>Submit pricing proposals for requested items and track approval status.</p>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php if (isset($_GET['success'])) { echo "<p class='success'>" . $_GET['success'] . "</p>"; } ?>

    <!-- Quotation Request Table -->
    <h3>📋 Quotation Requests</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Requested Price (LKR)</th>
            <th>Proposed Price (LKR)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr class="<?= strtolower($row['status']); ?>">
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= number_format($row['requested_price'], 2) ?></td>
            <td><?= ($row['proposed_price'] !== null) ? number_format($row['proposed_price'], 2) : 'N/A' ?></td>
            <td class="<?= strtolower($row['status']); ?>">
                <?= ucfirst($row['status']) ?>
            </td>
            <td>
                <?php if ($row['status'] === 'Requested') { ?>
                <form method="POST">
                    <input type="hidden" name="quotation_id" value="<?= $row['id'] ?>">
                    <input type="number" name="proposed_price" step="0.01" placeholder="Enter your price" required>
                    <button type="submit" class="submit-btn">📩 Submit Quotation</button>
                </form>
                <?php } else { ?>
                <span class="disabled">Submitted</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <button class="back-btn" onclick="window.location.href='profile.php'">🔙 Back to Profile</button>
</div>

</body>
</html>
