<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

// ✅ Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
ob_clean(); // ✅ Prevents white spaces before JSON output

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $rememberMe = isset($_POST['rememberMe']) ? 1 : 0;

    // ✅ Debugging: Log received email
    error_log("🔍 Checking login for email: " . $email);

    $query = "SELECT id, first_name, last_name, email, password, role, verified FROM users WHERE TRIM(LOWER(email)) = TRIM(LOWER(?))";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ✅ Debugging: Log found user info
        error_log("✅ Email Found: " . $user['email']);
        error_log("🔹 Role Found: " . $user['role']);

        if ($user['verified'] == 0) {
            error_log("❌ Email Not Verified: " . $user['email']);
            echo json_encode(["error" => "Please verify your email before logging in."]);
            exit();
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // ✅ Debugging: Confirm role before redirecting
            error_log("🚀 Redirecting Role: " . $user['role']);

            // ✅ Fix TechFix User Redirection
            $redirectPage = ($user['role'] == 'Admin') ? "../admin/dashboard.php" :
                (($user['role'] == 'Supplier') ? "../supplier/profile.php" :
                (($user['role'] == 'Customer') ? "../customer/profile.php" :
                "../frontend/login.php"));


            error_log("🚀 Final Redirect URL: " . $redirectPage); // Debugging Output

            // ✅ Send JSON Response
            echo json_encode(["success" => true, "redirect" => $redirectPage], JSON_UNESCAPED_SLASHES);
            exit();
        } else {
            error_log("❌ Invalid Password for: " . $email);
            echo json_encode(["error" => "Invalid email or password."]);
            exit();
        }
    } else {
        error_log("❌ No account found in Database for: " . $email);
        echo json_encode(["error" => "No account found with this email."]);
        exit();
    }
}

// ✅ If accessed directly without POST request
error_log("❌ Invalid request method.");
echo json_encode(["error" => "Invalid request."]);
exit();
