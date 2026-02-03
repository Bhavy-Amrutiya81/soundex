<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

session_start();

// Initialize UserManager
$userManager = new UserManager($pdo);

$message = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $message = "Both username and password are required.";
    } else {
        // Attempt to login user
        $result = $userManager->loginUser($username, $password);
        
        if ($result['success']) {
            // Set session variables
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['session_token'] = $result['session_token'];
            
            // Redirect to previous page or home
            $redirect = $_GET['redirect'] ?? 'home.php';
            header("Location: $redirect");
            exit();
        } else {
            $message = $result['message'];
        }
    }
}

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Soundex</title>
    <style>
        body {
            margin: 0; 
            padding: 0; 
            background-color: #50bbed; 
            font-family: 'Segoe UI', sans-serif; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        }

        .login-container {
            width: 370px; 
            padding: 25px; 
            background-color: white; 
            border-radius: 12px; 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); 
            animation: fadeZoom 0.8s ease;
            text-align: center;
        }

        .input-field {
            width: 100%; 
            padding: 10px; 
            margin-bottom: 12px; 
            border: 1px solid #ccc; 
            border-radius: 6px;
        }

        .submit-btn {
            width: 100%; 
            padding: 12px; 
            background-color: #0077cc; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #005fa3;
        }

        .signup {
            margin-top: 15px;
        }

        .signup a {
            color: #0077cc;
            text-decoration: none;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        @keyframes fadeZoom {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to Soundex</h2>
        <p>Access your account to continue shopping</p>
        
        <?php if (!empty($message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" class="input-field" required><br>
            <input type="password" name="password" placeholder="Password" class="input-field" required><br>
            <input type="submit" value="Login" class="submit-btn"><br>
        </form>
        <br>
        <div class="signup">Don't have an Account? <a href="signup.php">Sign up</a></div>
    </div>
</body>
</html>