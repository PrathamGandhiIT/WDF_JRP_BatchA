<?php
// Practical 8: Database Insertion via Prepared Statements
require_once "db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enrollment = trim($_POST["enrollment"] ?? "");
    $name       = trim($_POST["name"] ?? "");
    $username   = trim($_POST["username"] ?? "");
    $email      = trim($_POST["email"] ?? "");
    $mobile     = trim($_POST["mobile"] ?? "");
    $password   = $_POST["password"] ?? "";
    $course     = trim($_POST["course"] ?? "");
    $year       = (int)($_POST["year"] ?? 1);
    $gender     = $_POST["gender"] ?? "Male";

    if (empty($enrollment) || empty($name) || empty($username) || empty($email) || empty($password)) {
        $message = "Please fill in all required fields.";
        $messageType = "error";
    } else {
        $hashed_pwd = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO students (enrollment_number, name, username, email, mobile, password, course, year, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssssis", $enrollment, $name, $username, $email, $mobile, $hashed_pwd, $course, $year, $gender);
            if ($stmt->execute()) {
                $message = "Student record inserted successfully into database!";
                $messageType = "success";
            } else {
                $message = "Error inserting record: " . $stmt->error;
                $messageType = "error";
            }
            $stmt->close();
        } else {
            $message = "Database prepare failed: " . $conn->error;
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 8 - Database Connectivity</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; margin: 0; padding: 30px; }
        .container { max-width: 550px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #1a365d; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #2d3748; }
        input, select { width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; box-sizing: border-box; }
        .btn { background: #3182ce; color: #fff; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-size: 16px; font-weight: 600; }
        .btn:hover { background: #2b6cb0; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 15px; font-weight: 500; }
        .alert.success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .alert.error { background: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
    </style>
</head>
<body>
<div class="container">
    <h2>Practical 8: Student Registration (Database Insert)</h2>
    <?php if ($message): ?>
        <div class="alert <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="Register.php">
        <div class="form-group">
            <label>Enrollment Number:</label>
            <input type="text" name="enrollment" required placeholder="e.g. 25DIT018">
        </div>
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="name" required placeholder="e.g. Pratham Gandhi">
        </div>
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required placeholder="e.g. pratham_g">
        </div>
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" required placeholder="e.g. student@charusat.edu.in">
        </div>
        <div class="form-group">
            <label>Mobile Number:</label>
            <input type="tel" name="mobile" required placeholder="10-digit mobile number">
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Course:</label>
            <select name="course" required>
                <option value="Information Technology">Information Technology</option>
                <option value="Computer Engineering">Computer Engineering</option>
                <option value="Artificial Intelligence">Artificial Intelligence</option>
            </select>
        </div>
        <div class="form-group">
            <label>Year of Study:</label>
            <select name="year" required>
                <option value="1">1st Year</option>
                <option value="2" selected>2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>
        </div>
        <div class="form-group">
            <label>Gender:</label>
            <select name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <button type="submit" class="btn">Register Student</button>
    </form>
</div>
</body>
</html>
