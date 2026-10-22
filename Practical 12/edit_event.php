<?php
/**
 * Practical 12: Edit Event with Poster Update
 * Author: Raj Patel & Manish Kumar
 */
require "admin_check.php";
require "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    exit("Invalid event ID specified.");
}

$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    header("Location: events.php?message=" . urlencode("Event not found."));
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["event_name"] ?? "");
    $date = $_POST["event_date"] ?? "";
    $venue = trim($_POST["venue"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $poster = $event["poster"];

    if ($name === "" || $date === "" || $venue === "") {
        $error = "Event name, date, and venue are required.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $error = "Enter a valid date in YYYY-MM-DD format.";
    } elseif (mb_strlen($name) > 150 || mb_strlen($venue) > 150) {
        $error = "Name and venue must be 150 characters or fewer.";
    } elseif (isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["poster"];

        if ($file["error"] !== UPLOAD_ERR_OK) {
            $error = "Poster upload failed.";
        } elseif ($file["size"] > 2 * 1024 * 1024) {
            $error = "Poster must be 2 MB or smaller.";
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file["tmp_name"]);
            finfo_close($finfo);

            $types = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/webp" => "webp"
            ];

            if (!isset($types[$mime])) {
                $error = "Only JPG, PNG, and WebP images are allowed.";
            } else {
                $uploadsDir = __DIR__ . "/uploads";
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0755, true);
                }

                $newPoster = "poster_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $types[$mime];

                if (move_uploaded_file($file["tmp_name"], $uploadsDir . "/" . $newPoster)) {
                    // Remove old poster if exists
                    if (!empty($event["poster"]) && file_exists($uploadsDir . "/" . $event["poster"])) {
                        unlink($uploadsDir . "/" . $event["poster"]);
                    }
                    $poster = $newPoster;
                } else {
                    $error = "Could not save uploaded poster file.";
                }
            }
        }
    }

    if ($error === "") {
        $stmt = $conn->prepare(
            "UPDATE events SET event_name = ?, event_date = ?, venue = ?, description = ?, poster = ? WHERE event_id = ?"
        );
        $stmt->bind_param("sssssi", $name, $date, $venue, $description, $poster, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: events.php?message=" . urlencode("Event updated successfully."));
            exit;
        }

        $error = "Database update error: " . $conn->error;
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Practical 12</title>
    <link rel="stylesheet" href="Event.css">
    <style>
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #003366;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 9px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="Event.php">Public Events</a>
        <a href="events.php">Manage Events</a>
        <a href="add_event.php">+ Add New Event</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="form-container" style="max-width: 600px;">
        <h2>Edit Event #<?= (int)$id ?></h2>
        <p style="color: #666; font-size: 14px;">Update details and poster for this event (Practical 12)</p>

        <?php if ($error !== ""): ?>
            <p style="background: #fde8e8; color: #9b1c1c; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="edit_event.php?id=<?= (int)$id ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_name">Event Name *</label>
                <input type="text" id="event_name" name="event_name" required
                       value="<?= htmlspecialchars($_POST["event_name"] ?? $event["event_name"]) ?>">
            </div>

            <div class="form-group">
                <label for="event_date">Event Date *</label>
                <input type="date" id="event_date" name="event_date" required
                       value="<?= htmlspecialchars($_POST["event_date"] ?? $event["event_date"]) ?>">
            </div>

            <div class="form-group">
                <label for="venue">Event Venue *</label>
                <input type="text" id="venue" name="venue" required
                       value="<?= htmlspecialchars($_POST["venue"] ?? $event["venue"]) ?>">
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($_POST["description"] ?? $event["description"] ?? "") ?></textarea>
            </div>

            <div class="form-group">
                <label>Current Poster</label>
                <?php if (!empty($event["poster"]) && file_exists(__DIR__ . "/uploads/" . $event["poster"])): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="uploads/<?= htmlspecialchars($event["poster"]) ?>" alt="Poster" style="max-width: 140px; border-radius: 4px; display:block;">
                    </div>
                <?php else: ?>
                    <p style="color:#777; font-size:13px;">No current poster uploaded.</p>
                <?php endif; ?>

                <label for="poster">Replace Poster (Leave blank to keep current):</label>
                <input type="file" id="poster" name="poster" accept="image/jpeg,image/png,image/webp">
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Update Event</button>
                <a class="btn" href="events.php" style="background:#718096;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
