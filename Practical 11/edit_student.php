<?php
/**
 * Practical 11: Edit Student Record
 * Author: Raj Patel & Manish Kumar
 */
require_once "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: students.php?message=error");
    exit;
}

$stmt = $conn->prepare(
    "SELECT student_id, name, username, email, mobile, course, year, gender
     FROM students WHERE student_id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    header("Location: students.php?message=error");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = $_POST["course"] ?? "";
    $year = $_POST["year"] ?? "";
    $gender = $_POST["gender"] ?? "";

    if (
        $name === "" || $username === "" || $email === "" ||
        $mobile === "" || $course === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !in_array($year, ["1", "2", "3", "4"], true) ||
        !in_array($gender, ["Male", "Female"], true) ||
        !in_array($course, ["DIT", "BCA", "B.Tech", "MCA"], true)
    ) {
        $error = "Please provide valid information in all required fields.";
    } else {
        $stmt = $conn->prepare(
            "SELECT student_id FROM students
             WHERE (username = ? OR email = ?) AND student_id <> ?"
        );
        $stmt->bind_param("ssi", $username, $email, $id);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($exists) {
            $error = "The chosen username or email is already taken by another student.";
        } else {
            $yearValue = (int)$year;

            $stmt = $conn->prepare(
                "UPDATE students
                 SET name = ?, username = ?, email = ?, mobile = ?,
                     course = ?, year = ?, gender = ?
                 WHERE student_id = ?"
            );

            $stmt->bind_param(
                "sssssisi",
                $name,
                $username,
                $email,
                $mobile,
                $course,
                $yearValue,
                $gender,
                $id
            );

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: students.php?message=updated");
                exit;
            }

            $error = "Failed to update record. Please try again.";
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
    <title>Edit Student - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container form-container">
    <h1>Modify Student Information</h1>
    <p class="subtitle">Editing Record #<?= htmlspecialchars((string)$student["student_id"]) ?></p>

    <?php if ($error !== ""): ?>
        <p class="alert error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="edit_student.php?id=<?= (int)$id ?>">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required
               value="<?= htmlspecialchars($_POST["name"] ?? $student["name"]) ?>">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required
               value="<?= htmlspecialchars($_POST["username"] ?? $student["username"] ?? "") ?>">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required
               value="<?= htmlspecialchars($_POST["email"] ?? $student["email"]) ?>">

        <label for="mobile">Mobile Number</label>
        <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{10}"
               value="<?= htmlspecialchars($_POST["mobile"] ?? $student["mobile"]) ?>">

        <label for="course">Course</label>
        <?php $currentCourse = $_POST["course"] ?? $student["course"]; ?>
        <select id="course" name="course" required>
            <?php foreach (["DIT", "BCA", "B.Tech", "MCA"] as $opt): ?>
                <option value="<?= $opt ?>" <?= $currentCourse === $opt ? "selected" : "" ?>>
                    <?= $opt ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="year">Year of Study</label>
        <?php $currentYear = (string)($_POST["year"] ?? $student["year"]); ?>
        <select id="year" name="year" required>
            <?php foreach (["1", "2", "3", "4"] as $y): ?>
                <option value="<?= $y ?>" <?= $currentYear === $y ? "selected" : "" ?>>
                    Year <?= $y ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="gender">Gender</label>
        <?php $currentGender = $_POST["gender"] ?? $student["gender"]; ?>
        <select id="gender" name="gender" required>
            <option value="Male" <?= $currentGender === "Male" ? "selected" : "" ?>>Male</option>
            <option value="Female" <?= $currentGender === "Female" ? "selected" : "" ?>>Female</option>
        </select>

        <div style="margin-top: 20px;">
            <button type="submit" class="button primary">Update Record</button>
            <a class="button secondary" href="students.php">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
