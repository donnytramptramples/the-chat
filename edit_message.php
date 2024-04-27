<?php
// Connect to the SQLite database
$pdo = new PDO('sqlite:messages1.sqlite');

// Check if the request is a POST request to edit a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message ID and new content from the request data
    $messageId = $_POST['messageId'];
    $newContent = $_POST['newContent'];

    // Update the message content in the database
    $stmt = $pdo->prepare('UPDATE messages SET content = ? WHERE id = ?');
    $stmt->execute([$newContent, $messageId]);
}
?>