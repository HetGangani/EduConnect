<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));

    // Basic validation
    if(empty($name) || empty($email) || empty($username) || empty($password)) {
        die("Please fill all required fields.");
    }

    if($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare data string
    $dataLine = "Name: $name | Email: $email | Username: $username | Password: $hashedPassword\n";

    // File path
    $file = "registration_data.txt";

    // Append data to file
    if(file_put_contents($file, $dataLine, FILE_APPEND | LOCK_EX)) {
        echo "<h2>Registration successful!</h2>";
        echo "<p><a href='login.html'>Click here to login</a></p>";
    } else {
        echo "Error: Unable to save data. Check folder permissions.";
    }
}
?>

