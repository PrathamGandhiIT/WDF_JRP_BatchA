<?php
require_once "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: Register.html");
    exit;
}

$enrollment_number = strtoupper(trim($_POST["enrollment_number"] ?? ""));
$name = trim($_POST["name"] ?? "");
$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");
$mobile = trim($_POST["mobile"] ?? "");
$password = $_POST["password"] ?? "";
$confirmPassword = $_POST["confirmPassword"] ?? "";
$course = trim($_POST["course"] ?? "");
$year = trim($_POST["year"] ?? "");
$gender = trim($_POST["gender"] ?? "");
$terms = isset($_POST["terms"]);

if (
    $enrollment_number === "" ||
    $name === "" ||
    $username === "" ||
    $email === "" ||
    $mobile === "" ||
    $password === "" ||
    $confirmPassword === "" ||
    $course === "" ||
    $year === "" ||
    $gender === ""
) {
    exit('Please fill in all required fields. <a href="Register.html">Go Back</a>');
}

if (!preg_match('/^[0-9]{2}[A-Z]{3}[0-9]{3}$/', $enrollment_number)) {
    exit('Invalid enrollment number. Use a format such as 25DIT001. <a href="Register.html">Go Back</a>');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('Enter a valid email address. <a href="Register.html">Go Back</a>');
}

if ($password !== $confirmPassword) {
    exit('Passwords do not match. <a href="Register.html">Go Back</a>');
}

if (!$terms) {
    exit('Please accept the terms and conditions. <a href="Register.html">Go Back</a>');
}

$stmt = $conn->prepare(
    "SELECT student_id FROM students
     WHERE enrollment_number = ? OR username = ? OR email = ?
     LIMIT 1"
);

if (!$stmt) {
    exit("A database error occurred: " . $conn->error);
}

$stmt->bind_param("sss", $enrollment_number, $username, $email);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    exit('A student account with this enrollment number, username, or email already exists. <a href="Register.html">Try another</a>');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$yearValue = (int) $year;
$role = "student";

$stmt = $conn->prepare(
    "INSERT INTO students
    (enrollment_number, name, username, email, mobile, password, course, year, gender, role)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    exit("Database insert preparation error: " . $conn->error);
}

$stmt->bind_param(
    "ssssssisss",
    $enrollment_number,
    $name,
    $username,
    $email,
    $mobile,
    $hashedPassword,
    $course,
    $yearValue,
    $gender,
    $role
);

if ($stmt->execute()) {
    $newId = $stmt->insert_id;
    $stmt->close();

    // Log registration in audit log
    $auditStmt = $conn->prepare("INSERT INTO audit_logs (user_id, user_name, action) VALUES (?, ?, ?)");
    if ($auditStmt) {
        $action = "New student registered ($enrollment_number)";
        $auditStmt->bind_param("iss", $newId, $name, $action);
        $auditStmt->execute();
        $auditStmt->close();
    }

    echo '<!DOCTYPE html><html><head><title>Registration Successful</title><link rel="stylesheet" href="Login.css"></head><body>';
    echo '<div class="login" style="margin-top:50px;">';
    echo '<h2>Registration Successful!</h2>';
    echo '<p>Your student account has been created for <strong>' . htmlspecialchars($name) . '</strong> (' . htmlspecialchars($enrollment_number) . ').</p>';
    echo '<p style="margin-top:20px;"><a href="login.php" style="background:#003366; color:#fff; padding:10px 20px; text-decoration:none; border-radius:4px;">Proceed to Login</a></p>';
    echo '</div></body></html>';
    exit;
} else {
    exit("Registration could not be completed: " . $stmt->error);
}
?>
