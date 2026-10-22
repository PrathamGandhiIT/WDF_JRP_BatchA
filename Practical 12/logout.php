<?php
/**
 * Practical 12: Admin Logout
 * Author: Raj Patel & Manish Kumar
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

header("Location: Event.php");
exit;
?>
