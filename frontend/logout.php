<?php
session_start();
require_once __DIR__ . '/../backend/php/db_config.php';
require_once __DIR__ . '/../backend/php/UserManager.php';

// Initialize UserManager
$userManager = new UserManager($pdo);

// If user is logged in, invalidate the session in the database
if (isset($_SESSION['session_token'])) {
    $userManager->logoutUser($_SESSION['session_token']);
}

// Destroy all session data
session_destroy();

// Redirect to home page
header("Location: /Bhavya/frontend/index.php");
exit();
?>