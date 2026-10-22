<?php
/**
 * Practical 14: Audit Trail Log
 * Author: Raj Patel & Manish Kumar
 */
require_once "auth.php";
requireAdmin();
require_once "db.php";

$conn->query("
    CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        user_name VARCHAR(150) NOT NULL,
        action VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$logs = $conn->query(
    "SELECT id, user_id, user_name, action, created_at
     FROM audit_logs
     ORDER BY id DESC
     LIMIT 100"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log - Practical 14</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "menu.php"; ?>

    <main class="container">
        <h1>System Security & Audit Log</h1>
        <p>Real-time audit trail of logins, administrative modifications, and student updates (Practical 14)</p>

        <div class="panel table-container">
            <table>
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Activity Recorded</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($logs && $logs->num_rows > 0): ?>
                    <?php while ($log = $logs->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= (int) $log["id"] ?></td>
                            <td><?= (int) $log["user_id"] ?></td>
                            <td><strong><?= htmlspecialchars($log["user_name"]) ?></strong></td>
                            <td><?= htmlspecialchars($log["action"]) ?></td>
                            <td><?= htmlspecialchars($log["created_at"]) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; padding: 20px;">No audit records found yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
