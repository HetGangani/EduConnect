<?php
session_start();
require_once "db.php";

// Only admin can access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

// Get user ID
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = intval($_GET['id']);

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = preg_replace("/[^0-9]/", "", $_POST['phone']);
    $role = $_POST['role'];
    $password = $_POST['password']; // Optional password change

    $errors = [];
    if (strlen($username) < 4) $errors[] = "Username must be at least 4 characters.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (!preg_match("/^\d{10}$/", $phone)) $errors[] = "Phone must be 10 digits.";

    if (!empty($errors)) {
        foreach ($errors as $err) echo "<p>$err</p>";
    } else {
        // Update query
        if (!empty($password)) {
            // Hash password if updated
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, phone=?, role=?, password=? WHERE user_id=?");
            $stmt->execute([$username, $email, $phone, $role, $hashedPassword, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, phone=?, role=? WHERE user_id=?");
            $stmt->execute([$username, $email, $phone, $role, $user_id]);
        }
        header("Location: manage_users.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User | EduConnect Admin</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h2>Edit User</h2>
<form action="" method="POST">
    <label>Username:</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <label>Phone:</label>
    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required pattern="\d{10}"><br>

    <label>Role:</label>
    <select name="role">
        <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
    </select><br>

    <label>New Password (leave blank to keep current):</label>
    <input type="password" name="password"><br><br>

    <button type="submit">Update User</button>
</form>
<p><a href="../php/manage_users.php">Back to Manage Users</a></p>
</body>
</html>

