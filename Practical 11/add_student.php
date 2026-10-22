<?php
/**
 * Practical 11: Add Student Record
 * Author: Raj Patel & Manish Kumar
 */
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $password = $_POST["password"] ?? "";
    $course = $_POST["course"] ?? "";
    $year = $_POST["year"] ?? "";
    $gender = $_POST["gender"] ?? "";

    if (
        $name === "" || $username === "" || $email === "" ||
        $mobile === "" || $password === "" || $course === "" ||
        !in_array($year, ["1", "2", "3", "4"], true) ||
        !in_array($gender, ["Male", "Female"], true) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $error = "Please provide valid information in all required fields.";
    } else {
        $stmt = $conn->prepare(
            "SELECT student_id FROM students WHERE username = ? OR email = ?"
        );
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($exists) {
            $error = "Username or email is already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $yearValue = (int)$year;

            $stmt = $conn->prepare(
                "INSERT INTO students
                (name, username, email, mobile, password, course, year, gender)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssssis",
                $name,
                $username,
                $email,
                $mobile,
                $hashedPassword,
                $course,
                $yearValue,
                $gender
            );

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: students.php?message=added");
                exit;
            }

            $error = "Unable to register student. Please check database details.";
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container form-container">
    <h1>Enroll New Student</h1>
    <p class="subtitle">Enter student details below (Practical 11)</p>

    <?php if ($error !== ""): ?>
        <p class="alert error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="add_student.php">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="e.g. Raj Patel" required value="<?= htmlspecialchars($_POST["name"] ?? "") ?>">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="e.g. raj_patel" required value="<?= htmlspecialchars($_POST["username"] ?? "") ?>">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="e.g. raj.patel@example.com" required value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">

        <label for="mobile">Mobile Number</label>
        <input type="tel" id="mobile" name="mobile" placeholder="e.g. 9876543210" pattern="[0-9]{10}" title="Enter 10-digit mobile number" required value="<?= htmlspecialchars($_POST["mobile"] ?? "") ?>">

        <label for="password">Account Password</label>
        <input type="password" id="password" name="password" placeholder="Create a secure password" required>

        <label for="course">Course</label>
        <select id="course" name="course" required>
            <option value="">-- Choose Course --</option>
            <?php foreach (["DIT", "BCA", "B.Tech", "MCA"] as $opt): ?>
                <option value="<?= $opt ?>" <?= ($_POST["course"] ?? "") === $opt ? "selected" : "" ?>>
                    <?= $opt ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="year">Year of Study</label>
        <select id="year" name="year" required>
            <option value="">-- Choose Year --</option>
            <?php foreach (["1", "2", "3", "4"] as $y): ?>
                <option value="<?= $y ?>" <?= ($_POST["year"] ?? "") === $y ? "selected" : "" ?>>
                    Year <?= $y ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male" <?= ($_POST["gender"] ?? "") === "Male" ? "selected" : "" ?>>Male</option>
            <option value="Female" <?= ($_POST["gender"] ?? "") === "Female" ? "selected" : "" ?>>Female</option>
        </select>

        <div style="margin-top: 20px;">
            <button type="submit" class="button primary">Submit & Save</button>
            <a class="button secondary" href="students.php">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
