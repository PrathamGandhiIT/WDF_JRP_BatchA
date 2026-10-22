<?php
// Practical 10: Admin Dashboard (Role-Based Access Protected)
require_once "auth.php";
require_once "db.php";

check_role("admin");

// Fetch student count and students list
$count_res = $conn->query("SELECT COUNT(*) AS total FROM students");
$total_students = $count_res->fetch_assoc()['total'] ?? 0;

$students_res = $conn->query("SELECT student_id, enrollment_number, name, email, course, role FROM students ORDER BY student_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 10 - Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="navbar admin-nav">
    <div class="logo">StudentHub Admin Console</div>
    <div class="nav-right">
        <span>Admin: <strong><?= htmlspecialchars($_SESSION['name'] ?? 'Administrator') ?></strong></span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</div>

<div class="container">
    <div class="welcome-banner admin-banner">
        <h2>Administrator Control Panel</h2>
        <p>Role-Based Access Verified (Admin Privilege). Total registered users: <strong><?= $total_students ?></strong></p>
    </div>

    <div class="info-card">
        <h3>User Management Overview</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Enrollment</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $students_res->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['enrollment_number']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td><span class="badge <?= $row['role'] === 'admin' ? 'badge-admin' : 'badge-student' ?>"><?= htmlspecialchars($row['role']) ?></span></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
