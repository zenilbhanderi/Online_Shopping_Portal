<?php
session_start();

// If you want to clear session variables as well
$_SESSION = [];
session_unset();
session_destroy();

// Redirect user to the homepage
header("Location: index.php");
exit();
?>
