<?php
session_start();
$_SESSION = array();
session_destroy();

// Redirect to login with a logout message
header("Location: login.php?message=logged_out");
exit();
?>
