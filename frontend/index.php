<?php
session_start();

// Redirect to home page (Guest access allowed)
header("Location: pages/home.php");
exit();
?>