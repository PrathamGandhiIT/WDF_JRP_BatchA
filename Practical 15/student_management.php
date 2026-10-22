<?php
/**
 * Practical 15: Student Management for Administrators
 * Author: Raj Patel & Manish Kumar
 */
require_once "auth.php";
requireRole("admin");
require_once "db.php";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        $error = "Security token mismatch. Please reload the page and try again.";
    } else {
        $deleteId = filter_var(
            $_POST["delete_id"],
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($deleteId === false) {
            $error = "Invalid student ID specified.";
        } elseif ((int)$deleteId === (int)($_SESSION["student_id"] ?? 0)) {
            $error = "You cannot delete your own active administrator account.";
        } else {
            $stmt = $conn->prepare(
                "DELETE FROM students WHERE student_id = ? AND role = 'student'"
            );
            $stmt->bind_param("i", $deleteId);

            if ($stmt->execute() && $stmt->affected_rows === 1) {
                $message = "Student record deleted successfully.";
            } else {
                $error = "Student not found or the specified account is protected.";
            }

            $stmt->close();
        }
    }
}

$search = trim($_GET["search"] ?? "");

if (strlen($search) > 100) {
    $search = substr($search, 0, 100);
}

if ($search !== "") {
    $stmt = $conn->prepare(
        "SELECT student_id, enrollment_number, name, username, email, mobile, course, year, gender, role
         FROM students
         WHERE name LIKE ? OR enrollment_number LIKE ? OR username LIKE ?
         ORDER BY student_id DESC"
    );

    $term = "%" . $search . "%";
    $stmt->bind_param("sss", $term, $term, $term);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $students = $conn->query(
        "SELECT student_id, enrollment_number, name, username, email, mobile, course, year, gender, role
         FROM students
         ORDER BY student_id DESC"
    );
}

if (!$students) {
    die("Database query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - StudentHub (Practical 15)</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            color: #333;
        }

        .navbar {
            background: #003366;
            padding: 14px;
            text-align: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 12px;
            font-size: 15px;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #cfe8ff;
        }

        .container {
            width: 95%;
            max-width: 1350px;
            margin: 25px auto;
            background: white;
            padding: 24px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        h1, h2 {
            color: #003366;
            margin-top: 0;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex: 1;
            min-width: 240px;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 4px;
        }

        button, .button {
            padding: 10px 16px;
            background: #003366;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover, .button:hover {
            background: #0055aa;
        }

        .delete-button {
            background: #b42318;
            padding: 6px 12px;
            font-size: 13px;
        }

        .delete-button:hover {
            background: #991b1b;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #cfe8ff;
            color: #003366;
            font-weight: bold;
        }

        tr:hover {
            background: #f8fafc;
        }

        .message {
            background: #def7ec;
            color: #03543f;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .error {
            background: #fde8e8;
            color: #9b1c1c;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .badge {
            background: #ebf8ff;
            color: #2b6cb0;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
        }

        .footer {
            background: #003366;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="Home.html">Home</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="student_management.php">Manage Students</a>
    <a href="dashboard.php">Student Dashboard</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h1>Student Records Administration</h1>
    <p>Search, inspect, and manage university student profiles (Practical 15 | Raj & Manish)</p>

    <?php if ($message !== ""): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="search-form" method="GET" action="student_management.php">
        <input
            type="text"
            name="search"
            placeholder="Search by student name, enrollment number or username..."
            value="<?= htmlspecialchars($search) ?>"
        >
        <button type="submit">Search Records</button>
        <a class="button" href="student_management.php" style="background:#4a5568;">Clear Filter</a>
    </form>

    <h2>Active Student Directory</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Enrollment No</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email Address</th>
                    <th>Mobile</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($students && $students->num_rows > 0): ?>
                <?php while ($student = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$student["student_id"] ?></td>
                        <td><strong><?= htmlspecialchars($student["enrollment_number"] ?? "") ?></strong></td>
                        <td><?= htmlspecialchars($student["name"]) ?></td>
                        <td><?= htmlspecialchars($student["username"]) ?></td>
                        <td><?= htmlspecialchars($student["email"]) ?></td>
                        <td><?= htmlspecialchars($student["mobile"]) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($student["course"]) ?></span></td>
                        <td>Year <?= (int)$student["year"] ?></td>
                        <td><?= htmlspecialchars($student["gender"]) ?></td>
                        <td>
                            <?php if ($student["role"] === "student"): ?>
                                <form method="POST"
                                      action="student_management.php<?= $search !== "" ? "?search=" . urlencode($search) : "" ?>"
                                      onsubmit="return confirm('Are you sure you want to delete this student record?');" style="margin:0;">
                                    <input type="hidden" name="delete_id"
                                           value="<?= (int)$student["student_id"] ?>">
                                    <input type="hidden" name="csrf_token"
                                           value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
                                    <button class="delete-button" type="submit">Delete</button>
                                </form>
                            <?php else: ?>
                                <span style="color:#718096; font-size:12px; font-weight:bold;">Admin Account</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center; padding: 25px; color: #718096;">No student records found matching the search criteria.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="footer">
    <p>&copy; 2026 StudentHub Administration Panel | Practical 15</p>
</div>

</body>
</html>
