<?php
session_start();

if (isset($_SESSION["username"])) {
    // User is already logged in, redirect to chat.php
    header("Location: chat.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>The Chat</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body {
            background-image: url('');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forms {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            max-width: 400px;
            box-sizing: border-box;
        }

        .message {
            font-weight: bold;
            color: green;
            margin-top: 20px;
        }
    </style>
    <script>
        <?php
            if (isset($_GET["message"]) && $_GET["message"] === "signup_success") {
                echo "window.onload = function() {
                    alert('Succesfully Signed Up. Now sign in.');
                };";
            }
        ?>
    </script>
</head>
<body>
    <div class="container">
        <div class="forms">
            <h2>community chat</h2>
            <form action="login.php" method="POST">
                <h3>Login to your account</h3>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
            </form>
            <form action="signup.php" method="POST">
                <h3>Sign Up to create an account</h3>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                 
                <input type="submit" value="Sign Up">
            </form>
        </div>
    </div>
</body>
</html>