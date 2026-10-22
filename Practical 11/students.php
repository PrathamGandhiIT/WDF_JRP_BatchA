<?php
/**
 * Practical 11: Student Management System
 * Author: Raj Patel & Manish Kumar
 */
require_once "db.php";

$search = trim($_GET["search"] ?? "");
$course = trim($_GET["course"] ?? "");

$sql = "SELECT student_id, name, username, email, mobile, course, year, gender FROM students WHERE 1=1";
$params = [];
$types = "";

if ($search !== "") {
    $sql .= " AND (name LIKE ? OR username LIKE ? OR email LIKE ?)";
    $value = "%" . $search . "%";
    $params[] = $value;
    $params[] = $value;
    $params[] = $value;
    $types .= "sss";
}

if ($course !== "") {
    $sql .= " AND course = ?";
    $params[] = $course;
    $types .= "s";
}

$sql .= " ORDER BY student_id DESC";

$stmt = $conn->prepare($sql);

if ($types !== "") {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$students = $stmt->get_result();

$message = $_GET["message"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="header-bar">
        <h1>Student Records Management</h1>
        <p class="subtitle">Practical 11 | Maintained by Raj & Manish</p>
    </div>

    <?php if ($message === "added"): ?>
        <p class="alert success">Student record added successfully.</p>
    <?php elseif ($message === "updated"): ?>
        <p class="alert success">Student record updated successfully.</p>
    <?php elseif ($message === "deleted"): ?>
        <p class="alert success">Student record deleted successfully.</p>
    <?php elseif ($message === "error"): ?>
        <p class="alert error">Operation failed. Please verify the input values and retry.</p>
    <?php endif; ?>

    <div class="toolbar">
        <a class="button primary" href="add_student.php">+ Add New Student</a>
    </div>

    <form method="GET" action="students.php" class="search-form">
        <input type="text" name="search" placeholder="Search by name, username or email..."
               value="<?= htmlspecialchars($search) ?>">

        <select name="course">
            <option value="">All Courses</option>
            <?php foreach (["DIT", "BCA", "B.Tech", "MCA"] as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>"
                    <?= $course === $option ? "selected" : "" ?>>
                    <?= htmlspecialchars($option) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="button">Search / Filter</button>
        <a class="button secondary" href="students.php">Reset</a>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($students && $students->num_rows > 0): ?>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$row["student_id"]) ?></td>
                        <td><strong><?= htmlspecialchars($row["name"]) ?></strong></td>
                        <td><?= htmlspecialchars($row["username"] ?? "") ?></td>
                        <td><?= htmlspecialchars($row["email"]) ?></td>
                        <td><?= htmlspecialchars($row["mobile"]) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($row["course"]) ?></span></td>
                        <td>Year <?= htmlspecialchars((string)$row["year"]) ?></td>
                        <td><?= htmlspecialchars($row["gender"]) ?></td>
                        <td class="actions">
                            <a class="button small edit-btn" href="edit_student.php?id=<?= (int)$row["student_id"] ?>">Edit</a>
                            <form method="POST" action="delete_student.php"
                                  onsubmit="return confirm('Are you sure you want to delete this student record?');" style="display:inline;">
                                <input type="hidden" name="student_id"
                                       value="<?= (int)$row["student_id"] ?>">
                                <button class="button danger small" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align:center; padding: 25px; color:#666;">No student records found matching the criteria.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
