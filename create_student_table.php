<?php
require_once db.php";

try {
    // Create normalized student table
    $sql = "CREATE TABLE IF NOT EXISTS students (
        student_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        course VARCHAR(50),
        year INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "✅ Table 'students' created successfully!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}

?>
