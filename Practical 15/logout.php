<?php
/**
 * Practical 15: Logout Handler
 * Author: Raj Patel & Manish Kumar
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

header("Location: Home.html");
exit;
?>
