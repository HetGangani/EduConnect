<?php
require_once "db.php"; // Make sure db_connect.php points to your existing DB

try {
    $sql = "CREATE TABLE IF NOT EXISTS events (
        event_id INT AUTO_INCREMENT PRIMARY KEY,
        event_name VARCHAR(100) NOT NULL,
        event_date DATE NOT NULL,
        event_time TIME NOT NULL,
        location VARCHAR(100),
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "✅ Table 'events' created successfully!";
} catch (PDOException $e) {
    echo "❌ Error creating table: " . $e->getMessage();
}
?>

