<?php
/**
 * Practical 12: Admin Authorization Guard
 * Author: Raj Patel & Manish Kumar
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["role"]) || strtolower($_SESSION["role"]) !== "admin") {
    http_response_code(403);
    echo '<!DOCTYPE html><html><head><title>Access Denied</title><link rel="stylesheet" href="Event.css"></head><body>';
    echo '<div class="form-container" style="text-align:center; margin-top:50px;">';
    echo '<h2>Access Denied</h2>';
    echo '<p style="color:#666;">You must be logged in as an administrator to manage university events.</p>';
    echo '<p><a class="btn" href="login.php">Go to Admin Login</a> <a class="btn" href="login.php?demo=admin">1-Click Test Admin Login</a></p>';
    echo '<p><a href="Event.php" style="color:#003366;">&larr; Back to Public Events</a></p>';
    echo '</div></body></html>';
    exit;
}
?>
