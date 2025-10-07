<?php
session_start();
require 'db.php';

if(!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['event_date'];

    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date) VALUES (:title,:description,:date)");
    $stmt->execute(['title'=>$title, 'description'=>$desc, 'date'=>$date]);
}

// Fetch all events
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Events | EduConnect</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Events</h2>
<form method="POST">
<label>Title:</label><input type="text" name="title" required>
<label>Description:</label><textarea name="description"></textarea>
<label>Date:</label><input type="date" name="event_date" required>
<button type="submit">Add Event</button>
</form>

<h3>Upcoming Events</h3>
<ul>
<?php foreach($events as $e): ?>
<li><?php echo htmlspecialchars($e['title'])." (".$e['event_date'].")"; ?></li>
<?php endforeach; ?>
</ul>

<a href="dashboard.php">Back to Dashboard</a>
</div>
</body>

</html>
