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

// Use JS to clear localStorage and redirect
?>
<!DOCTYPE html>
<html>

<head>
    <title>Logging out...</title>
    <script>
        localStorage.removeItem('session_token');
        localStorage.removeItem('user');
        localStorage.removeItem('cart');
        window.location.href = 'index.php';
    </script>
</head>

<body>
    <p>Please wait while we log you out...</p>
</body>

</html>
<?php
exit();
?>