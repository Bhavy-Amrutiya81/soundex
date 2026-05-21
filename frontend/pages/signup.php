<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

session_start();

// Initialize UserManager
$userManager = new UserManager($pdo);

$message = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Attempt to register user using the correct method
        $result = $userManager->registerUser($username, $email, $password);
        
        if ($result['success']) {
            // Redirect to success page after successful registration
            header("Location: ../index.php");
            exit();
        } else {
            $message = $result['message'];
        }
    }
}

$isLoggedIn = isset($_SESSION['user_id']);
$username_nav = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Soundex Audio Solutions</title>
 
  <style>
    body {
      margin: 0;
      padding-top: 80px;
      background-color: #50bbed;
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .signup-container {
      width: 370px;
      padding: 25px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      animation: fadeZoom 0.8s ease;
      text-align: center;
      margin: 20px auto;
    }

    .signup-container h2 {
      margin-bottom: 10px;
      color: #0077cc;
    }

    .input-field {
      width: 100%;
      padding: 10px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
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
      font-size: 1rem;
    }

    .submit-btn:hover {
      background-color: #005fa3;
    }

    .login-link {
      margin-top: 15px;
    }

    .login-link a {
      color: #0077cc;
      text-decoration: none;
    }

    .error-message {
      color: red;
      margin-bottom: 10px;
    }

    @keyframes fadeZoom {
      0%   { opacity: 0; transform: scale(0.9); }
      100% { opacity: 1; transform: scale(1); }
    }
  </style>
</head>

<body>
  <?php
  // Use a temp variable so we don't override the form's $username
  $username = $username_nav;
 
  $username = $_POST['username'] ?? '';
  ?>

  <div class="signup-container">
    <h2>Welcome to Soundex</h2>
    <p>Where Every Beat Becomes Unforgettable</p>

    <?php if (!empty($message)): ?>
      <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" class="input-field" required><br>
      <input type="email" name="email" placeholder="Email ID" class="input-field" required><br>
      <input type="password" name="password" placeholder="Password" class="input-field" required><br>
      <input type="password" name="confirm_password" placeholder="Confirm Password" class="input-field" required><br>
      <button type="submit" class="submit-btn">Create Account</button>
    </form>
    <br>
    <div class="login-link"><a href="../index.php">Already have an account? Login here</a></div>
  </div>

</body>
</html>