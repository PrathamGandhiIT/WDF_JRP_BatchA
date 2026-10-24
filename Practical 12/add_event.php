<?php
require "admin_check.php";
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["event_name"] ?? "");
    $date = $_POST["event_date"] ?? "";
    $venue = trim($_POST["venue"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $poster = null;

    if ($name === "" || $date === "" || $venue === "") {
        $error = "Event name, date, and venue are mandatory.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $error = "Please enter a valid date in YYYY-MM-DD format.";
    } elseif (mb_strlen($name) > 150 || mb_strlen($venue) > 150) {
        $error = "Event name and venue cannot exceed 150 characters.";
    } elseif (isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["poster"];

        if ($file["error"] !== UPLOAD_ERR_OK) {
            $error = "Poster file upload failed with code " . $file["error"];
        } elseif ($file["size"] > 2 * 1024 * 1024) {
            $error = "Poster size must not exceed 2 MB.";
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
                $error = "Only JPG, PNG, and WebP format images are allowed.";
            } else {
                $uploadsDir = __DIR__ . "/uploads";
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0755, true);
                }

                $poster = "poster_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $types[$mime];

                if (!move_uploaded_file($file["tmp_name"], $uploadsDir . "/" . $poster)) {
                    $error = "Failed to save uploaded poster file.";
                    $poster = null;
                }
            }
        }
    }

    if ($error === "") {
        $stmt = $conn->prepare(
            "INSERT INTO events (event_name, event_date, venue, description, poster) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $name, $date, $venue, $description, $poster);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: events.php?message=" . urlencode("Event added successfully."));
            exit;
        }

        $error = "Database error while adding event: " . $conn->error;
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event - Practical 12</title>
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
        <h2>Create New University Event</h2>
        <p style="color: #666; font-size: 14px;">Fill out the form below to publish an event with poster (Practical 12)</p>

        <?php if ($error !== ""): ?>
            <p style="background: #fde8e8; color: #9b1c1c; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="add_event.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_name">Event Name *</label>
                <input type="text" id="event_name" name="event_name" placeholder="e.g. AI Workshop by Raj Patel" required value="<?= htmlspecialchars($_POST["event_name"] ?? "") ?>">
            </div>

            <div class="form-group">
                <label for="event_date">Event Date *</label>
                <input type="date" id="event_date" name="event_date" required value="<?= htmlspecialchars($_POST["event_date"] ?? "") ?>">
            </div>

            <div class="form-group">
                <label for="venue">Event Venue *</label>
                <input type="text" id="venue" name="venue" placeholder="e.g. Seminar Hall 2, IT Dept" required value="<?= htmlspecialchars($_POST["venue"] ?? "") ?>">
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Brief outline of schedule and coordinator details..."><?= htmlspecialchars($_POST["description"] ?? "") ?></textarea>
            </div>

            <div class="form-group">
                <label for="poster">Upload Event Poster (JPG, PNG, WebP &le; 2MB)</label>
                <input type="file" id="poster" name="poster" accept="image/jpeg,image/png,image/webp">
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Publish Event</button>
                <a class="btn" href="events.php" style="background:#718096;">Back to Events</a>
            </div>
        </form>
    </div>
</body>
</html>
