<?php
session_start();
require '../backend/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's quotations
$query = "SELECT * FROM quotations WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation History | TechFix</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>



<div class="container">
    <h2>Your Quotation Requests</h2>
    <a href="request_quotation.php" class="back-btn">⬅ Back to Request Quotations</a>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Requested Price</th>
                <th>Supplier Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['product_name']; ?></td>
                    <td>$<?php echo $row['requested_price']; ?></td>
                    <td>$<?php echo ($row['proposed_price']) ? $row['proposed_price'] : 'N/A'; ?></td>
                    <td class="<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../frontend/footer.php'; ?> 
</body>
</html>
