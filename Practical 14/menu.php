<?php
/**
 * Practical 14: Navigation Bar Component
 * Author: Raj Patel & Manish Kumar
 */
$role = strtolower((string) ($_SESSION["role"] ?? "student"));
?>
<nav class="navbar">
    <?php if ($role === "admin"): ?>
        <a href="index.php">Admin Dashboard</a>
        <a href="students.php">Students</a>
        <a href="events.php">Events</a>
        <a href="audit.php">Audit Log</a>
        <a href="Dashboard.php">Preview Student View</a>
    <?php else: ?>
        <a href="Dashboard.php">Student Dashboard</a>
        <a href="events.php">Campus Events</a>
    <?php endif; ?>

    <a href="logout.php">Logout</a>
</nav>
