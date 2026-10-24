<?php
require_once "auth.php";
requireAdmin();
require_once "db.php";

$result = $conn->query(
    "SELECT student_id, enrollment_number, name, username, email, course, year, role
     FROM students
     ORDER BY student_id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students - Practical 14</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "menu.php"; ?>

    <main class="container">
        <h1>Student Accounts Directory</h1>
        <p>Review all registered students and their respective details (Practical 14)</p>

        <div class="panel table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Enrollment No</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($student = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= (int) $student["student_id"] ?></td>
                            <td><strong><?= htmlspecialchars($student["enrollment_number"] ?? "-") ?></strong></td>
                            <td><?= htmlspecialchars($student["name"] ?? "") ?></td>
                            <td><?= htmlspecialchars($student["username"] ?? "") ?></td>
                            <td><?= htmlspecialchars($student["email"] ?? "") ?></td>
                            <td><?= htmlspecialchars($student["course"] ?? "-") ?></td>
                            <td><?= htmlspecialchars((string)($student["year"] ?? "-")) ?></td>
                            <td><span style="background:#e2e8f0; padding:2px 8px; border-radius:10px; font-size:12px;"><?= htmlspecialchars($student["role"] ?? "student") ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align: center; padding: 20px;">No students found in the database.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
