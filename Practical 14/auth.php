<?php
/**
 * Practical 14: Authentication & Authorization Middleware
 * Author: Raj Patel & Manish Kumar
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

function isLoggedIn() {
    return isset($_SESSION["student_id"]) && isset($_SESSION["role"]);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (strtolower((string) ($_SESSION["role"] ?? "")) !== "admin") {
        http_response_code(403);
        echo '<!DOCTYPE html><html><head><title>Access Denied</title><link rel="stylesheet" href="style.css"></head><body>';
        echo '<div class="container" style="text-align:center; margin-top:50px;">';
        echo '<h1>Access Denied</h1>';
        echo '<p>Admin privileges required to access this dashboard page.</p>';
        echo '<p><a href="Dashboard.php" class="btn">Return to Student Dashboard</a></p>';
        echo '</div></body></html>';
        exit;
    }
}

function redirectByRole() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }

    if (strtolower((string)$_SESSION["role"]) === "admin") {
        header("Location: index.php");
    } else {
        header("Location: Dashboard.php");
    }
    exit;
}

function setRememberToken($studentId) {
    // Optional cookie support
    setcookie("remember_student", (string)$studentId, time() + (86400 * 30), "/");
}

function clearRememberCookie() {
    if (isset($_COOKIE["remember_student"])) {
        setcookie("remember_student", "", time() - 3600, "/");
    }
}
?>
