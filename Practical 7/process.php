<?php
// Practical 7: PHP Form Processing with Server-Side Validation and JSON File Storage

$errors = [];
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");

    // Server-side validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name must contain letters and spaces only.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($mobile)) {
        $errors[] = "Mobile number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile must be a valid 10-digit number.";
    }

    if (empty($course)) {
        $errors[] = "Please select a course.";
    }

    // If validation succeeds, store in JSON and CSV
    if (empty($errors)) {
        $newRecord = [
            "name" => htmlspecialchars($name),
            "email" => htmlspecialchars($email),
            "mobile" => htmlspecialchars($mobile),
            "course" => htmlspecialchars($course),
            "created_at" => date("Y-m-d H:i:s")
        ];

        $dataDir = __DIR__ . "/data";
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0777, true);
        }

        // 1. JSON Storage
        $jsonFile = $dataDir . "/student.json";
        $currentJson = file_exists($jsonFile) && filesize($jsonFile) > 0 ? json_decode(file_get_contents($jsonFile), true) : [];
        if (!is_array($currentJson)) {
            $currentJson = [];
        }
        $currentJson[] = $newRecord;
        file_put_contents($jsonFile, json_encode($currentJson, JSON_PRETTY_PRINT));

        // 2. CSV Storage
        $csvFile = $dataDir . "/student.csv";
        $csvHandle = fopen($csvFile, "a");
        if (filesize($csvFile) == 0) {
            fputcsv($csvHandle, ["Name", "Email", "Mobile", "Course", "Timestamp"]);
        }
        fputcsv($csvHandle, [$name, $email, $mobile, $course, date("Y-m-d H:i:s")]);
        fclose($csvHandle);

        $successMsg = "Student registration saved successfully to JSON and CSV!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Practical 7 - PHP Form Processing</title>
    <link rel="stylesheet" href="admindashboard.css">
    <style>
        .form-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); font-family: Calibri, sans-serif; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn-submit { background: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Student Registration (Server-Side Processing)</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert-error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($successMsg): ?>
        <div class="alert-success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>

    <form method="POST" action="process.php">
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Mobile Number:</label>
            <input type="tel" name="mobile" value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Course:</label>
            <select name="course" required>
                <option value="">-- Select Course --</option>
                <option value="Information Technology">Information Technology</option>
                <option value="Computer Engineering">Computer Engineering</option>
                <option value="Artificial Intelligence">Artificial Intelligence</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Submit Registration</button>
    </form>
</div>
</body>
</html>
