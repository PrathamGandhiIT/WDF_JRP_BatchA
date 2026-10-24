<?php
require_once "auth.php";
requireAdmin();
require_once "db.php";

$totalUsers = 0;
$totalStudents = 0;
$totalEvents = 0;

$usersResult = $conn->query("SELECT COUNT(*) AS total FROM students");
if ($usersResult) {
    $totalUsers = (int) $usersResult->fetch_assoc()["total"];
    $totalStudents = $totalUsers;
}

$eventsResult = $eventsConn->query("SELECT COUNT(*) AS total FROM events");
if ($eventsResult) {
    $totalEvents = (int) $eventsResult->fetch_assoc()["total"];
}

$latest = $conn->query(
    "SELECT student_id, name, email, course, year
     FROM students
     ORDER BY student_id DESC
     LIMIT 5"
);

$auditMessage = "";
if (isset($_SESSION["dashboard_message"])) {
    $auditMessage = $_SESSION["dashboard_message"];
    unset($_SESSION["dashboard_message"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Practical 14</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "menu.php"; ?>

    <main class="container">
        <h1>Administrator Dashboard</h1>
        <p>Welcome back, <strong><?= htmlspecialchars($_SESSION["name"] ?? "Admin") ?></strong> (Practical 14)</p>

        <?php if ($auditMessage !== ""): ?>
            <p class="message"><?= htmlspecialchars($auditMessage) ?></p>
        <?php endif; ?>

        <section class="stats">
            <div class="stat-card">
                <h2>Total Registered</h2>
                <p><?= $totalUsers ?></p>
            </div>
            <div class="stat-card">
                <h2>Active Students</h2>
                <p><?= $totalStudents ?></p>
            </div>
            <div class="stat-card">
                <h2>Campus Events</h2>
                <p><?= $totalEvents ?></p>
            </div>
        </section>

        <section class="panel">
            <h2>Recent Student Registrations</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($latest && $latest->num_rows > 0): ?>
                        <?php while ($student = $latest->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) $student["student_id"]) ?></td>
                                <td><strong><?= htmlspecialchars($student["name"] ?? "") ?></strong></td>
                                <td><?= htmlspecialchars($student["email"] ?? "") ?></td>
                                <td><?= htmlspecialchars($student["course"] ?? "-") ?></td>
                                <td>Year <?= htmlspecialchars((string)($student["year"] ?? "-")) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center; padding: 15px;">No student records found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
