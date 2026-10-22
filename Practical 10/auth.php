<?php
// Practical 10: Authentication Middleware & Session Timeout Management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Session Timeout: Inactivity after 15 minutes (900 seconds)
$timeout_duration = 900;

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout_duration) {
        // Session expired
        session_unset();
        session_destroy();
        header("Location: login.php?msg=timeout");
        exit();
    }
}
$_SESSION['last_activity'] = time();

// 2. Check if user is logged in
function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?msg=login_required");
        exit();
    }
}

// 3. Role-Based Access Control Check
function check_role($required_role) {
    check_auth();
    if ($_SESSION['role'] !== $required_role) {
        header("Location: login.php?msg=unauthorized");
        exit();
    }
}
?>
