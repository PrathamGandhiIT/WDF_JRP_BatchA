<?php
// Practical 9: Database Connection Script
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "studenthub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
