<?php
/**
 * Practical 12: Admin Events Management
 * Author: Raj Patel & Manish Kumar
 */
require "admin_check.php";
require "db.php";

$result = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Practical 12</title>
    <link rel="stylesheet" href="Event.css">
</head>
<body>
    <div class="navbar">
        <a href="Event.php">Public Events</a>
        <a href="events.php">Manage Events</a>
        <a href="add_event.php">+ Add New Event</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="form-container">
        <h1>Campus Events Management</h1>
        <p style="text-align: center; color: #555; margin-bottom: 20px;">
            Admin Portal | Incharge: Raj Patel & Manish Kumar
        </p>

        <?php if (isset($_GET["message"])): ?>
            <p class="message" style="background:#def7ec; color:#03543f; padding:10px; border-radius:4px; text-align:center;">
                <?= htmlspecialchars($_GET["message"]) ?>
            </p>
        <?php endif; ?>

        <div style="margin-bottom: 15px; display: flex; gap: 10px;">
            <a class="btn" href="add_event.php">+ Add New Event</a>
            <a class="btn" href="Event.php" style="background:#4a5568;">View Public Page</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Poster</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$event["event_id"] ?></td>
                        <td>
                            <?php if (!empty($event["poster"]) && file_exists(__DIR__ . "/uploads/" . $event["poster"])): ?>
                                <img class="poster"
                                     src="uploads/<?= htmlspecialchars($event["poster"]) ?>"
                                     alt="Poster" style="max-width: 60px; max-height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <span style="font-size:12px; color:#888;">No poster</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($event["event_name"]) ?></strong></td>
                        <td><?= htmlspecialchars($event["event_date"]) ?></td>
                        <td><?= htmlspecialchars($event["venue"]) ?></td>
                        <td>
                            <a class="btn" href="edit_event.php?id=<?= (int)$event["event_id"] ?>" style="padding: 5px 10px; font-size:13px;">Edit</a>

                            <form method="post" action="delete_event.php"
                                  onsubmit="return confirm('Are you sure you want to delete this event?');"
                                  style="display:inline-block; margin:0;">
                                <input type="hidden" name="id" value="<?= (int)$event["event_id"] ?>">
                                <button type="submit" class="btn" style="background:#e53e3e; padding:5px 10px; font-size:13px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No events currently found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
