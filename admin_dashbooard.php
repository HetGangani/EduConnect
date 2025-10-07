<?php
session_start();
require_once "../php/db.php";

// Only allow admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/login.html");
    exit;
}

// Fetch all users
$stmt = $pdo->query("SELECT user_id, username, email, phone, role FROM users ORDER BY user_id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | EduConnect</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h2>Admin Dashboard - Manage Users</h2>
<p>Welcome, <?php echo $_SESSION['username']; ?> | <a href="../php/logout.php">Logout</a></p>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user['user_id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['phone']) ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <a href="./php/edit_users.php?id=<?= $user['user_id'] ?>">Edit</a> | 
                <a href="../php/delete_users.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
