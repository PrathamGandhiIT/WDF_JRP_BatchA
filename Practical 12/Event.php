<?php
/**
 * Practical 12: Public Events Listing
 * Author: Raj Patel & Manish Kumar
 */
require "db.php";

$result = $conn->query(
    "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC"
);

// Fallback in case past dates are loaded
if (!$result || $result->num_rows === 0) {
    $result = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAdmin = isset($_SESSION["role"]) && strtolower($_SESSION["role"]) === "admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Events - Practical 12</title>
    <link rel="stylesheet" href="Event.css">
</head>
<body>
    <div class="navbar">
        <a href="Event.php">Events</a>
        <a href="events.php">Manage Events</a>
        <?php if ($isAdmin): ?>
            <a href="logout.php">Logout (Admin)</a>
        <?php else: ?>
            <a href="login.php">Admin Login</a>
        <?php endif; ?>
    </div>

    <div class="events">
        <h1>Campus Events Portal</h1>
        <p>Explore upcoming events, technical workshops, and cultural fests at our university.</p>
        <p style="font-size: 13px; color: #555;">Coordinated by: Raj Patel & Manish Kumar</p>
    </div>

    <div class="upcoming">
        <h2>Upcoming Campus Events</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 120px;">Poster</th>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($event["poster"]) && file_exists(__DIR__ . "/uploads/" . $event["poster"])): ?>
                                <img class="poster"
                                     src="uploads/<?= htmlspecialchars($event["poster"]) ?>"
                                     alt="Poster" style="max-width: 90px; border-radius: 4px;">
                            <?php else: ?>
                                <div style="background:#e2e8f0; padding:15px 5px; font-size:12px; color:#64748b; border-radius:4px;">No Poster</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($event["event_name"]) ?></strong></td>
                        <td><?= htmlspecialchars($event["event_date"]) ?></td>
                        <td><?= htmlspecialchars($event["venue"]) ?></td>
                        <td style="text-align: left;"><?= nl2br(htmlspecialchars($event["description"] ?? "")) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No upcoming events scheduled at this moment.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
