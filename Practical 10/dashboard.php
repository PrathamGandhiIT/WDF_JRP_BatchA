<?php
// Practical 10: Student Dashboard (Protected by auth.php)
require_once "auth.php";
require_once "db.php";

check_auth();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT enrollment_number, name, username, email, mobile, course, year, gender, role FROM students WHERE student_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 10 - Student Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="navbar">
    <div class="logo">StudentHub Portal</div>
    <div class="nav-right">
        <span>Logged in as: <strong><?= htmlspecialchars($user['name'] ?? 'Student') ?></strong> (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</div>

<div class="container">
    <div class="welcome-banner">
        <h2>Welcome to StudentHub, <?= htmlspecialchars($user['name'] ?? 'Student') ?>!</h2>
        <p>Practical 10 Session & Authentication Verification Successful.</p>
    </div>

    <div class="info-card">
        <h3>My Student Profile</h3>
        <table class="profile-table">
            <tr><th>Enrollment Number:</th><td><?= htmlspecialchars($user['enrollment_number'] ?? 'N/A') ?></td></tr>
            <tr><th>Full Name:</th><td><?= htmlspecialchars($user['name'] ?? 'N/A') ?></td></tr>
            <tr><th>Username:</th><td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td></tr>
            <tr><th>Email:</th><td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td></tr>
            <tr><th>Mobile:</th><td><?= htmlspecialchars($user['mobile'] ?? 'N/A') ?></td></tr>
            <tr><th>Course:</th><td><?= htmlspecialchars($user['course'] ?? 'N/A') ?></td></tr>
            <tr><th>Academic Year:</th><td>Year <?= htmlspecialchars($user['year'] ?? '1') ?></td></tr>
            <tr><th>Role:</th><td><span class="badge"><?= htmlspecialchars($user['role'] ?? 'student') ?></span></td></tr>
        </table>
    </div>
</div>
</body>
</html>
