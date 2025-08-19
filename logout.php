<?php
session_start();


session_destroy();

// Redirect to index page (or login page)
header("Location: index.php");
exit;
?>
