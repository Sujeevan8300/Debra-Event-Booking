<?php
// Start the session (if not already started)
session_start();

// Destroy the user's session
session_destroy();

// Redirect the user to the login page or any other desired location
header("Location: login.php"); // Change "login.php" to the appropriate URL
exit();
?>
