<?php
// upload_file.php

// Assuming your SQLite database is messages1.sqlite and has a table named messages
$database_file = 'messages1.sqlite';

// Check if the file is uploaded
if(isset($_FILES['file']['name'])) {
    // File upload path
    $targetDir = "files/";
    $fileName = basename($_FILES['file']['name']);
    $targetFilePath = $targetDir . $fileName;

    // Upload file to the server
    if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
        // Insert file details into the database along with the username
        $username = $_POST['username'];
        $fileLink = "https://1f9fb58c-87f2-4100-a6c4-d0176d39624c-00-k8x94pfrjqpq.worf.replit.dev/" . $targetFilePath;
        $conn = new SQLite3($database_file);
        $stmt = $conn->prepare('INSERT INTO messages (username, content) VALUES (:username, :content)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':content', $fileLink);
        $stmt->execute();
        $conn->close();

        // Send success response
        echo json_encode(array('status' => 'success', 'fileName' => $fileName, 'fileLink' => $fileLink));
    }
} else {
    // Send error response if file is not uploaded
    echo json_encode(array('status' => 'error', 'message' => 'File is not uploaded.'));
}
?>
