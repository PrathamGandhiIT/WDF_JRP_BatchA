<?php
require_once "auth.php";
requireRole("admin");
require_once "db.php";

$totalStudents = 0;
$totalAdmins = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM students WHERE role = 'student'"
);
if ($result) {
    $totalStudents = (int)$result->fetch_assoc()["total"];
}

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM students WHERE role = 'admin'"
);
if ($result) {
    $totalAdmins = (int)$result->fetch_assoc()["total"];
}

$adminName = htmlspecialchars(
    $_SESSION["name"] ?? "Manish Sharma (Admin)",
    ENT_QUOTES,
    "UTF-8"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StudentHub (Practical 15)</title>
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
        .admin, .summary, .services, .notice, .activity {
            width: 85%;
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 24px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .admin {
            text-align: center;
        }
        h1, h2, h3 {
            color: #003366;
            margin-top: 0;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }
        .stat-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
        }
        .stat-card p {
            font-size: 32px;
            font-weight: bold;
            color: #003366;
            margin: 10px 0 0;
        }
        .service-links {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 15px;
        }
        .service-links a, .button {
            display: inline-block;
            background: #003366;
            color: white;
            padding: 10px 18px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        .service-links a:hover, .button:hover {
            background: #0055aa;
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
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="student_management.php">Manage Students</a>
    <a href="dashboard.php">Student Dashboard View</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="admin">
    <h1>Welcome, <?= $adminName ?>!</h1>
    <p>StudentHub Administration Panel | Practical 15 (Role-Based Access Control)</p>
    <p style="color: #666; font-size: 14px;">Coordinated by: Raj Patel & Manish Kumar</p>
</div>

<div class="summary">
    <h2>University Portal Summary</h2>
    <div class="stats">
        <div class="stat-card">
            <h3>Registered Students</h3>
            <p><?= $totalStudents ?></p>
        </div>
        <div class="stat-card">
            <h3>Admin Accounts</h3>
            <p><?= $totalAdmins ?></p>
        </div>
    </div>
</div>

<div class="services">
    <h2>Administrative Controls</h2>
    <div class="service-links">
        <a href="student_management.php">Manage Student Records</a>
        <a href="dashboard.php">Preview Student Dashboard</a>
        <a href="Home.html">View Public Portal</a>
    </div>
</div>

<div class="notice">
    <h2>Academic Announcements</h2>
    <p>Maintain university notices, department circulars, and registration schedules.</p>
</div>

<div class="footer">
    <p>&copy; 2026 StudentHub Administration | Practical 15</p>
</div>

</body>
</html>
