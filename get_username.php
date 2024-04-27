<?php
session_start();

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Create a JSON response with the username
    $response = array("username" => $username);
    echo json_encode($response);
} else {
    // If the username is not set in the session, return an empty response
    $response = array("username" => "");
    echo json_encode($response);
}
?>