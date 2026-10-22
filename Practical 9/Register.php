<?php
// Practical 9: Secure User Registration with Backend Validation & Password Hashing
require_once "db.php";

$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enrollment = trim($_POST["enrollment"] ?? "");
    $name       = trim($_POST["name"] ?? "");
    $username   = trim($_POST["username"] ?? "");
    $email      = trim($_POST["email"] ?? "");
    $mobile     = trim($_POST["mobile"] ?? "");
    $password   = $_POST["password"] ?? "";
    $course     = trim($_POST["course"] ?? "");
    $year       = (int)($_POST["year"] ?? 1);
    $gender     = $_POST["gender"] ?? "Male";

    // 1. Backend Validations
    if (empty($enrollment)) {
        $errors[] = "Enrollment number is required.";
    }

    if (empty($name) || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name must contain letters and spaces only.";
    }

    if (empty($username) || strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid email address.";
    }

    if (empty($mobile) || !preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile number must be a valid 10-digit number.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // 2. Duplicate Email & Username Check via Prepared Statement
    if (empty($errors)) {
        $checkStmt = $conn->prepare("SELECT student_id, email, username FROM students WHERE email = ? OR username = ? OR enrollment_number = ? LIMIT 1");
        if ($checkStmt) {
            $checkStmt->bind_param("sss", $email, $username, $enrollment);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $checkStmt->bind_result($dupId, $dupEmail, $dupUsername);
                $checkStmt->fetch();

                if (strcasecmp($dupEmail, $email) === 0) {
                    $errors[] = "Email address '$email' is already registered. Please use another email or login.";
                } elseif (strcasecmp($dupUsername, $username) === 0) {
                    $errors[] = "Username '$username' is already taken. Please choose another username.";
                } else {
                    $errors[] = "Enrollment number '$enrollment' is already registered in the system.";
                }
            }
            $checkStmt->close();
        }
    }

    // 3. Password Hashing and Database Insert
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $insertStmt = $conn->prepare("INSERT INTO students (enrollment_number, name, username, email, mobile, password, course, year, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'student')");
        if ($insertStmt) {
            $insertStmt->bind_param("sssssssis", $enrollment, $name, $username, $email, $mobile, $hashedPassword, $course, $year, $gender);
            if ($insertStmt->execute()) {
                $successMessage = "Registration successful! Your account has been securely created with hashed password credentials.";
            } else {
                $errors[] = "Registration failed due to a database error: " . $insertStmt->error;
            }
            $insertStmt->close();
        } else {
            $errors[] = "System error: Failed to prepare database query.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 9 - Registration Processing</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; margin: 0; padding: 40px 20px; }
        .card { max-width: 550px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #1a365d; }
        .alert { padding: 14px 18px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; line-height: 1.5; }
        .alert.error { background: #fed7d7; color: #742a2a; border-left: 4px solid #e53e3e; }
        .alert.success { background: #c6f6d5; color: #22543d; border-left: 4px solid #38a169; }
        ul { margin: 8px 0 0 16px; padding: 0; }
        .btn-link { display: inline-block; padding: 10px 18px; background: #3182ce; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 10px; }
        .btn-link:hover { background: #2b6cb0; }
    </style>
</head>
<body>
<div class="card">
    <h2>Registration Status</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <strong>Please correct the following errors:</strong>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <a href="Register.html" class="btn-link">&larr; Return to Registration Form</a>
    <?php elseif ($successMessage): ?>
        <div class="alert success">
            <strong>Success:</strong> <?= htmlspecialchars($successMessage) ?>
        </div>
        <p>You can now log in using your registered username/email and secure password.</p>
        <a href="Register.html" class="btn-link">Register Another Student</a>
    <?php else: ?>
        <p>No registration data received.</p>
        <a href="Register.html" class="btn-link">Go to Registration Form</a>
    <?php endif; ?>
</div>
</body>
</html>
