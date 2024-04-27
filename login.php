<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $users = [];
    if (file_exists("users.json")) {
        $users = json_decode(file_get_contents("users.json"), true);
    }

    foreach ($users as $user) {
        if ($user["username"] === $username && password_verify($password, $user["password"])) {
            $_SESSION["username"] = $username; // Store the username in the session
            header("Location: chat.php");
            exit();
        }
    }

    echo '<script>alert("invalid username or password."); window.location.href = "index.php";</script>';
}
?>