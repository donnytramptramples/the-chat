<?php
// Connect to the SQLite database
$pdo = new PDO('sqlite:messages1.sqlite');

// Check if the request is a POST request to delete a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message ID from the request data
    $messageId = $_POST['messageId'];

    // Delete the message from the database
    $stmt = $pdo->prepare('DELETE FROM messages WHERE id = ?');
    $stmt->execute([$messageId]);
}
?>