<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../html/login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EduConnect</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php if($_SESSION['role'] === 'admin'): ?>
    <a href="../php/manage_events.php">Manage Events</a>
    <?php endif; ?>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?> 👋</h1>
    <p>Your Role: <?php echo htmlspecialchars($_SESSION["role"]); ?></p>
    <p>You are logged in successfully!</p>

    <a href="../php/logout.php">Logout</a>
</body>
</html>
