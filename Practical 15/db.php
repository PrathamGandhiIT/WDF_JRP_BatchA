<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "studenthub";
$port = 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database connection failed. Please ensure MySQL is running and '$database' is imported: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
