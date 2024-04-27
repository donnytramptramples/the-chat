<?php
session_start();

// Perform any sign-out logic here
// For example, you can clear session data or perform any necessary cleanup

// Clear the session data
session_unset();
session_destroy();

// Redirect the user back to the index.php after signing out
header("Location: index.php");
exit;
?>