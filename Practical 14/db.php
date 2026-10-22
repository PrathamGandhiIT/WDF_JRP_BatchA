<?php
/**
 * Practical 14: Multi-Database Connections (studenthub & events_db)
 * Author: Raj Patel & Manish Kumar
 */

$host = "localhost";
$username = "root";
$password = "";
$port = 3306;

// Connection to studenthub
$conn = new mysqli($host, $username, $password, "studenthub", $port);
if ($conn->connect_error) {
    die("StudentHub database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Connection to events_db
$eventsConn = new mysqli($host, $username, $password, "events_db", $port);
if ($eventsConn->connect_error) {
    // If events_db does not exist, fallback to studenthub
    $eventsConn = $conn;
} else {
    $eventsConn->set_charset("utf8mb4");
}
?>
