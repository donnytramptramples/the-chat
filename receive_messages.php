<?php
// Connect to the SQLite database
$pdo = new PDO('sqlite:messages1.sqlite');

// Create the messages table if it doesn't exist
$pdo->exec('CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT, content TEXT)');

// Check if the request is a POST request to send a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message and username from the request data
    $message = $_POST['message'];
    $username = $_POST['username'];

    // Insert the message into the database
    $stmt = $pdo->prepare('INSERT INTO messages (username, content) VALUES (?, ?)');
    $stmt->execute([$username, $message]);

    // Get the last inserted message ID
    $messageId = $pdo->lastInsertId();

    // Return the message ID as a response
    echo $messageId;
    exit;
}

// Retrieve all messages from the database
$stmt = $pdo->query('SELECT id, username, content FROM messages');
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the messages as a JSON response
header('Content-Type: application/json');
echo json_encode($messages);
?>