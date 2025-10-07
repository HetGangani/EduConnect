<?php
$host = 'localhost';
$dbname = 'educonnect'; // Your DB name
$user = 'root';         // Usually root for XAMPP
$pass = '';             // Usually empty for XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
