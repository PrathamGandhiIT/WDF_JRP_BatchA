<?php
// Practical 10: Secure Login Processing with Sessions & Role-Based Access
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";
$info = "";

// Check message query params
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'logged_out') {
        $info = "You have been logged out successfully.";
    } elseif ($_GET['msg'] === 'timeout') {
        $error = "Your session expired due to inactivity. Please log in again.";
    } elseif ($_GET['msg'] === 'login_required') {
        $error = "Please log in to access your dashboard.";
    } elseif ($_GET['msg'] === 'unauthorized') {
        $error = "You do not have permission to access that page.";
    }
}

// Redirect already logged in user
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim($_POST["identifier"] ?? "");
    $password   = $_POST["password"] ?? "";
    $remember   = isset($_POST["remember"]);

    if (empty($identifier) || empty($password)) {
        $error = "Please enter both username/email and password.";
    } else {
        $stmt = $conn->prepare("SELECT student_id, name, username, email, password, role FROM students WHERE email = ? OR username = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                // Verify Password Hashing
                if (password_verify($password, $user['password'])) {
                    // Prevent Session Fixation: Regenerate Session ID
                    session_regenerate_id(true);

                    $_SESSION['user_id']       = $user['student_id'];
                    $_SESSION['name']          = $user['name'];
                    $_SESSION['username']      = $user['username'];
                    $_SESSION['role']          = $user['role'];
                    $_SESSION['last_activity'] = time();

                    // Optional Remember-Me Cookie (stores username for convenience, 7 days)
                    if ($remember) {
                        setcookie("remember_user", $user['username'], time() + (7 * 24 * 60 * 60), "/");
                    } else {
                        setcookie("remember_user", "", time() - 3600, "/");
                    }

                    // Role-Based Redirection
                    if ($user['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit();
                } else {
                    $error = "Invalid password. Please check your credentials.";
                }
            } else {
                $error = "User not found with that username or email.";
            }
            $stmt->close();
        }
    }
}

$remembered_user = $_COOKIE['remember_user'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 10 - Secure StudentHub Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <h2>StudentHub Login</h2>
        <p class="subtitle">Practical 10: Secure Sessions & Role-Based Access</p>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($info): ?>
            <div class="alert success"><?= htmlspecialchars($info) ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php">
            <div class="form-group">
                <label for="identifier">Username or Email:</label>
                <input type="text" id="identifier" name="identifier" value="<?= htmlspecialchars($remembered_user) ?>" required placeholder="Enter username or email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember" <?= $remembered_user ? "checked" : "" ?>> Remember me
                </label>
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>
    </div>
</div>
<script src="login.js"></script>
</body>
</html>
