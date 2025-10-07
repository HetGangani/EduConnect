<?php
session_start();
require_once "../php/db.php";

// Only admin access
if (!isset($_SESSION['username'])) {
    echo "❌ Access denied. Please <a href='login.html'>login</a>.";
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    echo "❌ Access denied. Admins only.";
    exit;
}

// Handle Add Event
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_event'])) {
    $name = trim($_POST['event_name']);
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    if ($name && $date && $time) {
        $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_time, location, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $date, $time, $location, $description]);
        $message = "✅ Event added successfully!";
    } else {
        $message = "⚠️ Name, Date, and Time are required!";
    }
}

// Handle Delete Event
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM events WHERE event_id = ?")->execute([$id]);
    header("Location: ../php/manage_events.php");
    exit;
}

// Handle Update Event
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_event'])) {
    $id = intval($_POST['event_id']);
    $name = trim($_POST['event_name']);
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);

    $stmt = $pdo->prepare("UPDATE events SET event_name = ?, event_date = ?, event_time = ?, location = ?, description = ? WHERE event_id = ?");
    $stmt->execute([$name, $date, $time, $location, $description, $id]);
    header("Location: ../php/manage_events.php");
    exit;
}

// Fetch events for display
$events = $pdo->query("SELECT * FROM events ORDER BY event_date, event_time ASC")->fetchAll(PDO::FETCH_ASSOC);

// If editing, fetch single event
$edit_event = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->execute([$id]);
    $edit_event = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Events | EduConnect</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    form { margin-bottom: 20px; }
    input, textarea { margin: 5px 0; padding: 5px; width: 100%; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f0f0f0; }
    .msg { color: green; font-weight: bold; margin: 10px 0; }
    .actions a { margin-right: 5px; text-decoration: none; }
</style>
</head>
<body>
<h1>📅 Events Management</h1>

<?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

<h2><?= $edit_event ? "Edit Event" : "Add New Event" ?></h2>
<form method="POST">
    <input type="hidden" name="event_id" value="<?= $edit_event['event_id'] ?? '' ?>">
    <label>Event Name:</label>
    <input type="text" name="event_name" value="<?= $edit_event['event_name'] ?? '' ?>" required>

    <label>Date:</label>
    <input type="date" name="event_date" value="<?= $edit_event['event_date'] ?? '' ?>" required>

    <label>Time:</label>
    <input type="time" name="event_time" value="<?= $edit_event['event_time'] ?? '' ?>" required>

    <label>Location:</label>
    <input type="text" name="location" value="<?= $edit_event['location'] ?? '' ?>">

    <label>Description:</label>
    <textarea name="description"><?= $edit_event['description'] ?? '' ?></textarea>

    <button type="submit" name="<?= $edit_event ? 'update_event' : 'add_event' ?>">
        <?= $edit_event ? "Update Event" : "Add Event" ?>
    </button>
    <?php if($edit_event): ?>
        <a href="../php/manage_events.php">Cancel Edit</a>
    <?php endif; ?>
</form>

<h2>All Events</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php foreach($events as $e): ?>
    <tr>
        <td><?= $e['event_id'] ?></td>
        <td><?= htmlspecialchars($e['event_name']) ?></td>
        <td><?= $e['event_date'] ?></td>
        <td><?= $e['event_time'] ?></td>
        <td><?= htmlspecialchars($e['location']) ?></td>
        <td><?= htmlspecialchars($e['description']) ?></td>
        <td class="actions">
            <a href="?edit=<?= $e['event_id'] ?>">Edit</a>
            <a href="?delete=<?= $e['event_id'] ?>" onclick="return confirm('Delete this event?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
