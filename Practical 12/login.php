<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if (isset($_GET["demo"]) && $_GET["demo"] === "admin") {
    $_SESSION["role"] = "admin";
    $_SESSION["name"] = "Admin (Raj Patel)";
    header("Location: events.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (($username === "admin" && $password === "admin123") || ($username === "raj" && $password === "raj123")) {
        $_SESSION["role"] = "admin";
        $_SESSION["name"] = $username === "raj" ? "Raj Patel" : "Admin";
        header("Location: events.php");
        exit;
    } else {
        $error = "Invalid username or password. (Hint: admin / admin123)";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Practical 12</title>
    <link rel="stylesheet" href="Event.css">
    <style>
        .login-card {
            max-width: 420px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error-box {
            background: #fde8e8;
            color: #9b1c1c;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .demo-box {
            margin-top: 20px;
            padding: 12px;
            background: #ebf8ff;
            border-radius: 6px;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="Event.php">Events</a>
        <a href="events.php">Manage Events</a>
        <a href="login.php">Admin Login</a>
    </div>

    <div class="login-card">
        <h2 style="text-align: center; margin-top:0;">Event Admin Login</h2>
        <p style="text-align: center; color: #666; font-size: 14px;">Sign in to manage and upload events</p>

        <?php if ($error !== ""): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="e.g. admin" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="e.g. admin123" required>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 10px;">Login</button>
        </form>

        <div class="demo-box">
            <p><strong>Quick Test Evaluation:</strong></p>
            <p>Default credentials: <code>admin</code> / <code>admin123</code></p>
            <p style="margin-top: 8px;"><a href="login.php?demo=admin" class="btn" style="display:inline-block; font-size: 12px; padding: 6px 12px;">Instant 1-Click Demo Login</a></p>
        </div>
    </div>
</body>
</html>
