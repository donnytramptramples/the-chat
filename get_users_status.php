<?php
// Read the users data from users.json
$usersData = file_get_contents("users.json");
$users = json_decode($usersData, true);

// Get the online status of each user
$onlineUsers = [];
foreach ($users as $user) {
    // Check if the user is currently logged in
    $onlineStatus = false; // Assuming user is offline by default

    // Implement your own logic to determine the online status of each user
    // For example, you can check if the user has an active session or if they are currently accessing the chat website

    // Let's assume that if the user has an active session, they are considered online
    session_id($user['session_id']);
    session_start();
    
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $onlineStatus = true;
    }

    // Add the user and their online status to the array
    $onlineUsers[] = [
        'username' => $user['username'],
        'online' => $onlineStatus
    ];
}

// Return the online users as JSON
header('Content-Type: application/json');
echo json_encode($onlineUsers);
?>