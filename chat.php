<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        #messages {
            max-height: 300px;
            overflow-y: scroll;
            background-color: #f2f2f2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #messages p {
            margin: 5px 0;
        }

        #message {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .sendBtn, .sendFileBtn, .statusBar {
            width: 15%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .signOutBtn {
            width: 10%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .sendBtn:hover, .sendFileBtn:hover, .statusBar:hover, .signOutBtn:hover {
            background-color: #45a049;
        }

        .deleteBtn {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
        }

        .deleteBtn:hover {
            background-color: #c82333;
        }

        .sidebar {
            width: 200px;
            background-color: #f2f2f2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            float: left;
            margin-right: 20px;
        }

        .user {
            margin-bottom: 10px;
        }

        .user span {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .user .online {
            background-color: green;
        }

        .user .offline {
            background-color: white;
        }

        .signOutBtn {
            position: absolute;
            top: 1px;
            right: 10px;
            z-index: 1;
        }

        .progressBar {
            width: 100%;
            height: 20px;
            background-color: #ddd;
            border-radius: 10px;
            margin-top: 10px;
            overflow: hidden;
        }

        .progressBarInner {
            height: 100%;
            background-color: #4CAF50;
            text-align: center;
            line-height: 20px;
            color: white;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var username = "";

            // Retrieve username from external URL
            $.ajax({
                type: "GET",
                url: "https://1f9fb58c-87f2-4100-a6c4-d0176d39624c-00-k8x94pfrjqpq.worf.replit.dev/get_username.php",
                dataType: "json",
                success: function (response) {
                    username = response.username;
                    if (username !== "") {
                        initializeChat(username);
                    } else {
                        window.location.href = "https://1f9fb58c-87f2-4100-a6c4-d0176d39624c-00-k8x94pfrjqpq.worf.replit.dev/index.php";
                    }
                },
                error: function () {
                    // Handle error case
                    console.log("Failed to retrieve username");
                }
            });


            function initializeChat(username) {
                function updateUsers() {
                    $.ajax({
                        type: "GET",
                        url: "get_users_status.php",
                        dataType: "json",
                        success: function (response) {
                            $(".sidebar").empty();

                            response.forEach(function (user) {
                                var userStatus = "<span class='" + (user.online ? "online" : "offline") + "'></span>";
                                var userContent = "<div class='user'>" + userStatus + user.username + "</div>";
                                $(".sidebar").append(userContent);
                            });

                            setTimeout(updateUsers, 3000); // Update user status every 3 seconds
                        }
                    });
                }

                updateUsers();

                function updateMessages() {
                    $.ajax({
                        type: "GET",
                        url: "receive_messages.php",
                        data: {username: username},
                        dataType: "json",
                        success: function (response) {
                            $("#messages").empty();

                            response.forEach(function (message) {
                                var deleteButton = "<button class='deleteBtn' data-message-id='" + message.id + "'>Delete</button>";
                                var fileLink = "";
                                if (message.content.startsWith("https://1f9fb58c-87f2-4100-a6c4-d0176d39624c-00-k8x94pfrjqpq.worf.replit.dev/files/")) {
                                    fileLink = "<br><a href='" + message.content + "' target='_blank'>" + message.content + "</a>";
                                }
                                var messageContent = "<p><strong>" + message.username + ":</strong> " + processMessageContent(message.content) + " " + deleteButton + fileLink + "</p>";
                                $("#messages").append(messageContent);
                            });

                            $(".deleteBtn").click(function () {
                                var messageId = $(this).data("message-id");
                                deleteMessage(messageId);
                            });

                            setTimeout(updateMessages, 3000); // Update messages every 3 seconds
                        }
                    });
                }

                updateMessages();

                $("#username").text(username);

                function sendMessage(message) {
                    if (message !== "") {
                        $.ajax({
                            type: "POST",
                            url: "receive_messages.php",
                            data: {username: username, message: message},
                            success: function () {
                                $("#message").val(""); // Clear the input field after sending the message
                            }
                        });
                    }
                }

                $("#sendBtn").click(function () {
                    var message = $("#message").val();
                    sendMessage(message);
                });

                $("#message").keypress(function (event) {
                    if (event.which === 13) {
                        event.preventDefault();
                        var message = $("#message").val();
                        sendMessage(message);
                    }
                });

                $("#signOutBtn").click(function () {
                    $.ajax({
                        type: "POST",
                        url: "sign_out.php",
                        data: {username: username},
                        success: function () {
                            window.location.href = "index.php"; // Redirect to the sign-in page after signing out
                        }
                    });
                });

                $("#sendFileBtn").click(function () {
                    var fileInput = document.getElementById('fileInput');
                    var file = fileInput.files[0];
                    var formData = new FormData();
                    formData.append('file', file);
                    formData.append('username', username); // Add this line to include the username

                    $.ajax({
                        type: "POST",
                        url: "upload_file.php",
                        data: formData,
                        processData: false,
                        contentType: false,
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total) * 100;
                                    var mbComplete = evt.loaded / (1024 * 1024); // Convert bytes to MB
                                    $(".progressBarInner").width(percentComplete + "%");
                                    $(".progressBarInner").text(percentComplete.toFixed(0) + "% (" + mbComplete.toFixed(2) + " MB)");
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function () {
                            $(".statusBar").text("Uploading file...");
                            $(".progressBarInner").width("0%");
                            $(".progressBarInner").text("0%");
                        },
                        success: function (response) {
                            $(".statusBar").text("");
                            $(".progressBarInner").width("100%");
                            $(".progressBarInner").text("100%");
                            if (response.status === "success") {
                                var fileLink = "https://1f9fb58c-87f2-4100-a6c4-d0176d39624c-00-k8x94pfrjqpq.worf.replit.dev/files/" + encodeURIComponent(response.fileName);
                                var message = '<a href="' + fileLink + '" target="_blank">' + response.fileName + '</a>';
                                sendMessage(message); // Send the message after setting the content
                            } else {
                                $(".statusBar").text("File sent!");
                            }
                        },
                        error: function () {
                            $(".statusBar").text("Failed to upload file.");
                        }
                    });
                });

                function deleteMessage(messageId) {
                    $.ajax({
                        type: "POST",
                        url: "delete_messages.php",
                        data: {messageId: messageId},
                        success: function () {
                            console.log("Message deleted successfully");
                        },
                        error: function () {
                            console.log("Failed to delete message");
                        }
                    });
                }

                function processMessageContent(content) {
                    return content.replace(/\s+/g, '_');
                }
            }
        });
    </script>
</head>
<body>
<div class="container">
    <div class="sidebar"></div>
    <div>
        <h2>Welcome, <span id="username"></span>!</h2>
        <div id="messages"></div>
        <input type="text" id="message" placeholder="Type your message...">
        <button class="sendBtn" id="sendBtn">Send</button>
        <input type="file" id="fileInput">
        <button class="sendFileBtn" id="sendFileBtn">Send File</button>
        <span class="statusBar"></span>
        <div class="progressBar">
            <div class="progressBarInner"></div>
        </div>
        <button class="signOutBtn" id="signOutBtn">Sign Out</button>
    </div>
</div>
</body>
</html>
