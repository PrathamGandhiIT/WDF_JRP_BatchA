<?php
/**
 * Practical 15: Student Dashboard
 * Author: Raj Patel & Manish Kumar
 */
require_once "auth.php";
requireRole("student");
require_once "db.php";

$id = (int)($_SESSION["student_id"] ?? 0);

$stmt = $conn->prepare(
    "SELECT enrollment_number, name, username, email, course, year, mobile
     FROM students WHERE student_id = ? LIMIT 1"
);

$student = null;
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$student) {
    // Fallback for session student
    $student = [
        "enrollment_number" => $_SESSION["enrollment_number"] ?? "25DIT001",
        "name" => $_SESSION["name"] ?? "Raj Patel",
        "username" => $_SESSION["username"] ?? "raj_patel",
        "email" => $_SESSION["email"] ?? "raj.patel@charusat.edu.in",
        "course" => "DIT",
        "year" => 2,
        "mobile" => "9876543210"
    ];
}

function studentValue($value) {
    return htmlspecialchars((string)($value ?? ""), ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - StudentHub (Practical 15)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .navbar {
            background: #003366;
            padding: 14px;
            text-align: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 12px;
            font-size: 15px;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #cfe8ff;
        }
        .container, .student-summary {
            width: 85%;
            max-width: 850px;
            margin: 25px auto;
            background: white;
            padding: 25px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        h1, h2 {
            color: #003366;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background: #cfe8ff;
            color: #003366;
            width: 35%;
        }
        .footer {
            background: #003366;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<nav class="navbar" id="navbar">
    <a href="Home.html">Home</a>
    <a href="dashboard.php">Student Dashboard</a>
    <?php if (strtolower((string)($_SESSION["role"] ?? "")) === "admin"): ?>
        <a href="admin_dashboard.php">Admin Panel</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h1>Welcome, <?= studentValue($student["name"]) ?>!</h1>
    <p>Student Portal Dashboard | Practical 15 (Maintained by Raj Patel & Manish Kumar)</p>
</div>

<div class="student-summary">
    <h2>Student Academic Profile</h2>
    <table>
        <thead>
            <tr>
                <th>Profile Field</th>
                <th>Registered Information</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Enrollment Number</td><td><strong><?= studentValue($student["enrollment_number"]) ?></strong></td></tr>
            <tr><td>Full Name</td><td><?= studentValue($student["name"]) ?></td></tr>
            <tr><td>Username</td><td><?= studentValue($student["username"]) ?></td></tr>
            <tr><td>Email Address</td><td><?= studentValue($student["email"]) ?></td></tr>
            <tr><td>Course / Program</td><td><?= studentValue($student["course"]) ?></td></tr>
            <tr><td>Current Academic Year</td><td>Year <?= studentValue($student["year"]) ?></td></tr>
        </tbody>
    </table>
</div>

<div class="footer">
    <p>&copy; 2026 StudentHub Portal | Practical 15</p>
</div>

</body>
</html>
