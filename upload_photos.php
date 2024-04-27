<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $photoData = file_get_contents($_FILES['photo']['tmp_name']);
    $base64Data = base64_encode($photoData);
    
    // Save base64 encoded image data to photos.json
    $photoEntry = [
        'timestamp' => time(),
        'sender' => 'User', // Replace with the appropriate sender name
        'imageData' => 'data:image/jpeg;base64,' . $base64Data
    ];
    
    $photos = [];
    if (file_exists('file.json')) {
        $photos = json_decode(file_get_contents('photos.json'), true);
    }
    
    $photos[] = $photoEntry;
    file_put_contents('photos.json', json_encode($photos));
    
    // Redirect back to the chat.php page after sending the photo
    header('Location: chat.php');
    exit;
}
?>