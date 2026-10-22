<?php
/**
 * Practical 14: Student Dashboard
 * Author: Raj Patel & Manish Kumar
 */
require_once "auth.php";
requireLogin();

$studentId = (int)($_SESSION["student_id"] ?? 0);

$stmt = $conn->prepare(
    "SELECT student_id, name, enrollment_number, course, year, email, mobile
     FROM students
     WHERE student_id = ?
     LIMIT 1"
);

$student = null;
if ($stmt) {
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

if (!$student) {
    // Fallback if record was demo or placeholder
    $student = [
        "student_id" => $studentId,
        "name" => $_SESSION["name"] ?? "Raj Patel",
        "enrollment_number" => $_SESSION["enrollment_number"] ?? "25DIT001",
        "course" => "DIT",
        "year" => 2,
        "email" => "raj.patel@charusat.edu.in",
        "mobile" => "9876543210"
    ];
}

$enrollmentNumber = $student["enrollment_number"] ?? "";
$displayName = !empty($student["name"]) ? $student["name"] : $enrollmentNumber;
$course = $student["course"] ?? "DIT";
$year = $student["year"] ?? "2";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - StudentHub Portal (Practical 14)</title>
    <link rel="stylesheet" href="Dashboard.css">
</head>
<body>

<div class="navbar">
    <a href="Dashboard.php">Dashboard</a>
    <a href="events.php">Events</a>
    <?php if (strtolower((string)($_SESSION["role"] ?? "")) === "admin"): ?>
        <a href="index.php">Admin Panel</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</div>

<div class="weather">
    <h2>Current Campus Weather</h2>
    <div class="weather-box">
        <p>Location: <span id="location">Loading live weather for Changa...</span></p>
        <p>Temperature: <span id="temperature">--</span></p>
        <p>Humidity: <span id="humidity">--</span></p>
        <p>Condition: <span id="condition">--</span></p>
    </div>
</div>

<div class="dashboard">
    <h1>Welcome, <?= htmlspecialchars($displayName, ENT_QUOTES, "UTF-8") ?>!</h1>
    <p>Welcome to your StudentHub Dashboard. Practical 14 role-based view maintained by Raj Patel & Manish Kumar.</p>
</div>

<div class="summary">
    <h2>Student Summary</h2>
    <table border="1">
        <tr>
            <th>Details</th>
            <th>Information</th>
        </tr>
        <tr>
            <td>Enrollment Number</td>
            <td><strong><?= htmlspecialchars($enrollmentNumber !== "" ? $enrollmentNumber : "25DIT001", ENT_QUOTES, "UTF-8") ?></strong></td>
        </tr>
        <tr>
            <td>Department</td>
            <td>Information Technology (DIT)</td>
        </tr>
        <tr>
            <td>Course</td>
            <td><?= htmlspecialchars((string) $course, ENT_QUOTES, "UTF-8") ?></td>
        </tr>
        <tr>
            <td>Year</td>
            <td>Year <?= htmlspecialchars((string) $year, ENT_QUOTES, "UTF-8") ?></td>
        </tr>
        <tr>
            <td>Student Name</td>
            <td><?= htmlspecialchars($displayName, ENT_QUOTES, "UTF-8") ?></td>
        </tr>
    </table>
</div>

<div class="services">
    <h2>Dashboard Services</h2>
    <ul>
        <li>&#9745; View Academic Notices & Announcements</li>
        <li>&#9745; Check Upcoming Campus Events & Hackathons</li>
        <li>&#9745; View Student Profile Information</li>
        <li>&#9745; Real-time Open-Meteo Campus Weather Widget</li>
    </ul>
</div>

<div class="notice">
    <h2>Latest Academic Notices</h2>
    <table border="1">
        <tr>
            <th>Notice Title</th>
            <th>Publication Date</th>
        </tr>
        <tr>
            <td>Semester Registration for 2026-27 Started</td>
            <td>01-Aug-2026</td>
        </tr>
        <tr>
            <td>Web Development Practical Submission (Practicals 11-15)</td>
            <td>10-Aug-2026</td>
        </tr>
        <tr>
            <td>National Hackathon Registration Open (Coordinator: Manish Kumar)</td>
            <td>15-Aug-2026</td>
        </tr>
    </table>
</div>

<div class="footer">
    <p>&copy; 2026 StudentHub Portal | Practical 14 | Developed by Raj Patel & Manish Kumar</p>
</div>

<script src="Dashboard.js"></script>

</body>
</html>
<?php
$conn->close();
if ($eventsConn !== $conn) {
    $eventsConn->close();
}
?>
