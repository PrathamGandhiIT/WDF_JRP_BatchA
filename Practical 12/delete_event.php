<?php
require "admin_check.php";
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: events.php?message=" . urlencode("Invalid event ID."));
    exit;
}

$stmt = $conn->prepare("SELECT poster FROM events WHERE event_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    header("Location: events.php?message=" . urlencode("Event record not found."));
    exit;
}

$stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();

    if (!empty($event["poster"])) {
        $path = __DIR__ . "/uploads/" . basename($event["poster"]);
        if (is_file($path)) {
            unlink($path);
        }
    }

    header("Location: events.php?message=" . urlencode("Event and poster deleted successfully."));
    exit;
}

$stmt->close();
header("Location: events.php?message=" . urlencode("Could not delete event."));
exit;
?>
