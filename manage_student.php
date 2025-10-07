<?php
require_once "db.php";

// Handle insert form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_student'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $year = intval($_POST['year']);

    if (!empty($name) && !empty($email)) {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, course, year) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $course, $year]);
        $message = "✅ Student added successfully!";
    } else {
        $message = "⚠️ Name and email are required!";
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM students WHERE student_id = ?")->execute([$id]);
    header("Location: manage_students.php");
    exit;
}

// Handle search
$searchTerm = $_GET['search'] ?? '';
if (!empty($searchTerm)) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE ?");
    $stmt->execute(["%$searchTerm%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
}
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students | StudentHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .msg { margin: 10px 0; color: green; font-weight: bold; }
        .delete { color: red; text-decoration: none; }
    </style>
</head>
<body>
<h1>🎓 Student Management</h1>

<?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

<h2>Add New Student</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="course" placeholder="Course">
    <input type="number" name="year" placeholder="Year">
    <button type="submit" name="add_student">Add Student</button>
</form>

<h2>Search Students</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Enter name..." value="<?= htmlspecialchars($searchTerm) ?>">
    <button type="submit">Search</button>
    <a href="manage_students.php">Reset</a>
</form>

<h2>Student List</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Year</th>
        <th>Created</th>
        <th>Action</th>
    </tr>
    <?php foreach ($students as $stu): ?>
    <tr>
        <td><?= $stu['student_id'] ?></td>
        <td><?= htmlspecialchars($stu['name']) ?></td>
        <td><?= htmlspecialchars($stu['email']) ?></td>
        <td><?= htmlspecialchars($stu['course']) ?></td>
        <td><?= htmlspecialchars($stu['year']) ?></td>
        <td><?= $stu['created_at'] ?></td>
        <td><a class="delete" href="?delete=<?= $stu['student_id'] ?>" onclick="return confirm('Delete this student?')">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>

