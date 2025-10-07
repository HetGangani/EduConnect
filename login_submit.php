<?php
session_start();
require_once "db.php"; // include your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user from DB
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) { // or password_verify($password, $user['password']) if hashed
        // ✅ Store session info
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: manage_events.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        echo "<p>❌ Invalid username or password.</p>";
    }
}
?>

