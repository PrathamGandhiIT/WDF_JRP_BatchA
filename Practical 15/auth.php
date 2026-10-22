<?php
/**
 * Practical 15: Role-Based Authentication & Guard System
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

function redirectByRole() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }

    if (strtolower((string)($_SESSION["role"] ?? "")) === "admin") {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

function requireRole($role) {
    requireLogin();

    $userRole = strtolower((string)($_SESSION["role"] ?? ""));
    $targetRole = strtolower($role);

    if ($userRole !== $targetRole) {
        // If an admin is visiting a student page, they can be allowed or redirected
        if ($userRole === "admin" && $targetRole === "student") {
            // Admin allowed to view student view or redirect to admin dashboard
            return;
        }

        redirectByRole();
    }
}
?>
