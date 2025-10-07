<?php
session_start();
require_once "../php/db.php";

// Only admin can access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/login.html");
    exit;
}

// Get user ID
if (!isset($_GET['id'])) {
    header("Location: ../php/manage_users.php");
    exit;
}

$user_id = intval($_GET['id']);

// Prevent admin from deleting self
if ($_SESSION['username'] === 'admin' && $user_id == 1) {
    echo "You cannot delete the main admin!";
    exit;
}

// Delete user
$stmt = $pdo->prepare("DELETE FROM users WHERE user_id=?");
$stmt->execute([$user_id]);

header("Location: ../php/manage_users.php");
exit;
?>
