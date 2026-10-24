<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "events_db";
$port = 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed. Please ensure MySQL is running and '$database' is imported."
    ]);
    exit;
}

$conn->set_charset("utf8mb4");
?>
