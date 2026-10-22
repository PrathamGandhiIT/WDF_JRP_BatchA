<?php
// Practical 8: Database Connection with MySQLi
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "studenthub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");
?>
