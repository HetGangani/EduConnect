<?php
// create_db.php - safe database + table creation script

$host = 'localhost';
$dbname = 'educonnect';   // <- set your DB name here
$dbuser = 'root';         // XAMPP default
$dbpass = '';             // XAMPP default (empty string)

// Basic safety: ensure $dbname is not empty
if (empty($dbname)) {
    die("Error: \$dbname is empty. Set \$dbname at the top of this file.");
}

try {
    // 1) Connect to MySQL server (no database selected)
    $pdo = new PDO("mysql:host=$host", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);

    echo "Connected to MySQL server successfully.<br>";

    // 2) Create database if not exists (use backticks)
    $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    $pdo->exec($sqlCreateDB);
    echo "Database `<strong>$dbname</strong>` created or already exists.<br>";

    // 3) Connect to the newly created database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected to database `<strong>$dbname</strong>`.<br>";

    // 4) Create users table
    $createUsers = <<<SQL
    CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user'
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;
    $pdo->exec($createUsers);
    echo "Table `users` created or already exists.<br>";
    try {
    $sql = "ALTER TABLE users
            MODIFY COLUMN password VARCHAR(255) NOT NULL";

    $pdo->exec($sql);
    echo "✅ Password column updated successfully to VARCHAR(255)!";
    } catch (PDOException $e) {
        echo "❌ Error updating table: " . $e->getMessage();
    }

    // 5) Insert sample users only if they don't exist
    // We'll insert only if no user with that username exists
    $sampleUsers = [
        ['admin', 'admin@123', 'admin'],
        ['student1', 'stud123', 'user'],
        ['student2', 'pass321', 'user'],
    ];

    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE username = ?");
    $stmtInsert = $pdo->prepare("INSERT INTO `users` (username, password, role) VALUES (?, ?, ?)");

    foreach ($sampleUsers as $u) {
        list($uname, $pwd, $role) = $u;
        $stmtCheck->execute([$uname]);
        $count = (int)$stmtCheck->fetchColumn();
        if ($count === 0) {
            $stmtInsert->execute([$uname, $pwd, $role]);
            echo "Inserted sample user `<strong>$uname</strong>`.<br>";
        } else {
            echo "User `<strong>$uname</strong>` already exists — skipping insert.<br>";
        }
    }

    echo "<br>✅ Database initialization complete!";
} catch (PDOException $e) {
    // Show a helpful error message; in production log instead
    echo "<strong>❌ Error:</strong> " . htmlspecialchars($e->getMessage());
    exit;
}
?>
