<?php
session_start();

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// Redirect admin to the main user index page
header("Location: ../index.php");
exit();
?>
