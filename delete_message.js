$(document).on("click", ".deleteBtn", function() {
    var messageId = $(this).data("message-id");

    // Get the username of the user who sent the message
    var messageUsername = $(this).closest('p').find('strong').text().replace(':', '').trim();

    // Check if the current user is the owner of the message
    if (messageUsername === "<?php echo $_GET["username"]; ?>") {
        $.ajax({
            type: "POST",
            url: "delete_message.php",
            data: { messageId: messageId },
            success: function() {
                updateMessages();
            }
        });
    } else {
        // Handle the case when the user is not the owner of the message
        // For example, you can display an error message or restrict the deletion action
        console.log("You are not authorized to delete this message.");
    }
});