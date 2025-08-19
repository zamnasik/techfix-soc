<?php
include '../backend/db.php';
session_start();

// Check if Admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch users from the existing users table
$query = "SELECT id, first_name, last_name, email, role, status FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>


<div class="admin-header">
    <h2>Admin Panel - User Management</h2>
    <button class="logout-btn" onclick="window.location.href='../backend/logout.php'">🚪 Logout</button>
</div>

<div class="admin-container">
    <h2>User Management</h2>
    
    <!-- Add User Button -->
    <button class="add-user-btn" onclick="openAddUserModal()">➕ Add New User</button>
    
    <!-- User Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['role'] ?></td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" onchange="toggleStatus(<?= $row['id'] ?>, '<?= $row['status'] ?>')" <?= ($row['status'] == 'Active') ? 'checked' : '' ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <button class="edit-btn" onclick="editUser(<?= $row['id'] ?>)">✏️ Edit</button>
                            <button class="delete-btn" onclick="deleteUser(<?= $row['id'] ?>)">❌ Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<!-- Add User Modal -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <h3>Add New User</h3>
        <input type="text" id="firstName" placeholder="First Name" required>
        <input type="text" id="lastName" placeholder="Last Name" required>
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Password" required>
        <input type="text" id="phone" placeholder="Phone Number" required>
        <select id="role">
            <option value="TechFix User">TechFix User</option>
            <option value="Supplier">Supplier</option>
            <option value="Admin">Admin</option>
        </select>
        <button onclick="addUser()">➕ Add User</button>
        <button onclick="closeAddUserModal()">❌ Cancel</button>
    </div>
</div>





<script src="../frontend/js/admin_users.js"></script>
</body>
</html>
