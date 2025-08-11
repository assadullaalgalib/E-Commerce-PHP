<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to index page (or login page)
header("Location: index.php");
exit;
?>
