<?php

session_start();

// Remove all session information
$_SESSION = [];

// Destroy session
session_destroy();

// Return to account page
header("Location: ../HTML/account.php");

exit;

?>