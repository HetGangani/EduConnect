<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = preg_replace("/[^0-9]/", "", $_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate
    $errors = [];
    if (strlen($username) < 4) $errors[] = "Username must be at least 4 characters.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (!preg_match("/^\d{10}$/", $phone)) $errors[] = "Phone must be 10 digits.";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match.";

    if (!empty($errors)) {
        foreach ($errors as $err) echo "<p>$err</p>";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
        $stmt->execute([$username, $hashedPassword]);
        header("Location: success.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<p>Email or Username already exists!</p>";
        } else {
            echo "<p>Database Error: ".$e->getMessage()."</p>";
        }
    }
}
?>

