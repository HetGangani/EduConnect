<?php
session_start();
require_once "db.php";

// Ensure only admin can access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

// Fetch all users
$stmt = $pdo->query("SELECT user_id, username, email, phone, role FROM users ORDER BY user_id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | EduConnect</title>
<link rel="stylesheet" href="style.css">
<style>
body {
    font-family: "Poppins", sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 0;
}
.dashboard-container {
    width: 90%;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
}
h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background-color: #007bff;
    color: white;
}
td a {
    text-decoration: none;
    color: #007bff;
    margin: 0 5px;
}
td a:hover {
    text-decoration: underline;
}
.logout {
    float: right;
    background-color: #dc3545;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    text-decoration: none;
}
.logout:hover {
    background-color: #c82333;
}
</style>
</head>
<body>
<div class="dashboard-container">
    <h2>Admin Dashboard - Manage Users</h2>
    <a href="logout.php" class="logout">Logout</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['user_id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['phone']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <a href="edit_user.php?id=<?= $user['user_id'] ?>">Edit</a> |
                <a href="delete_user.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

