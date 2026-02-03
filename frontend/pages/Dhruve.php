<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

// Initialize UserManager
$userManager = new UserManager($pdo);

$message = '';
$messageType = '';

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $message = 'Both username and password are required.';
        $messageType = 'error';
    } else {
        // Attempt to login user
        $result = $userManager->loginUser($username, $password);
        
        if ($result['success']) {
            // Set session variables
            session_start();
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['session_token'] = $result['session_token'];
            
            // Redirect to home page after successful login
            header("Location: home.php");
            exit();
        } else {
            $message = $result['message'];
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="D_css.css">
</head>
<body>
    <div class="login-container">
        <?php if ($message): ?>
          <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php endif; ?>
        
        <form method="POST" class="login-form">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" class="input-field" required>
            <input type="password" name="password" placeholder="Password" class="input-field" required>
            <button type="submit" class="submit">Login</button>
        </form>
    </div>
</body>
</html>