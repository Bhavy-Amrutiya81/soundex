<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

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
?>
<!DOCTYPE html>
<html>
<head>
  <title>Soundex Sign Up</title>
</head>
<body style="
  margin: 0; 
  padding: 0; 
  background-color: #50bbed; 
  font-family: 'Segoe UI', sans-serif; 
  height: 100vh; 
  display: flex; 
  align-items: center; 
  justify-content: center;
">

  <div style="
    width: 370px; 
    padding: 25px; 
    background-color: white; 
    border-radius: 12px; 
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); 
    animation: fadeZoom 0.8 ease;
    text-align: center;">

    <h2 style="margin-bottom: 20px; color: #0077cc;">Welcome to Soundex</h2>
    <p>Where Every Beat Becomes Unforgettable</p>
    
    <?php if (!empty($message)): ?>
        <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" 
        style="width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px;" required><br>

      <input type="email" name="email" placeholder="Email ID" 
        style="width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px;" required><br>

      <input type="password" name="password" placeholder="Password" 
        style="width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px;" required><br>

      <input type="password" name="confirm_password" placeholder="Confirm Password" 
        style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 6px;" required><br>

      <button type="submit" 
        style="width: 100%; padding: 12px; background-color: #0077cc; color: white; border: none; border-radius: 6px; cursor: pointer;
        transition: background-color 0.3s;">
        Create Account
      </button>
    </form>
    <br>
    <a href="../index.php" style="color: #0077cc; text-decoration: none;">Already have an account? Login here</a>
  </div>

  <style>
    @keyframes fadeZoom {
      0% { opacity: 0; transform: scale(0.9); }
      100% { opacity: 1; transform: scale(1); }
    }

    button:hover {
      background-color: #005fa3;
    }
  </style>

</body>
</html>