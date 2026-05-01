<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ContactManager.php';
require_once '../../backend/php/UserManager.php';

// Initialize ContactManager and UserManager
$contactManager = new ContactManager($pdo);
$userManager = new UserManager($pdo);

$message = '';
$messageType = '';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

if ($_POST) {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $messageText = trim($_POST['message'] ?? '');

  // Validate input
  if (empty($name) || empty($email) || empty($messageText)) {
    $message = 'All fields are required.';
    $messageType = 'error';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = 'Invalid email format.';
    $messageType = 'error';
  } else {
    // Save contact message to database
    $result = $contactManager->submitMessage($name, $email, '', $messageText);

    if ($result['success']) {
      $message = $result['message'];
      $messageType = 'success';
      // Clear form after successful submission
      $name = $email = $messageText = '';
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
  <title>Contact Us - Soundex Audio Solutions</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/header.css?v=1.3" />
  <link rel="stylesheet" href="../css/shared.css?v=1.3">
  <link rel="stylesheet" href="../css/contact.css?v=1.3">
</head>

<body>
  <!-- Navigation Header -->
  <?php include '../includes/header.php'; ?>

  <main class="main-content">
    <section class="contact-section section-padding">
      <h1 class="section-title">Contact Us</h1>
      <p class="intro" style="text-align: center; margin-bottom: 40px; color: #666;">We'd love to hear from you. Whether
        it's a question, feedback, or collaboration idea—drop us a message.</p>

      <div class="contact-container">
        <?php if ($message): ?>
          <div
            style="padding: 15px; margin-bottom: 20px; border-radius: 8px; <?php echo $messageType === 'error' ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'; ?>">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="contact-form">
          <input type="text" name="name" placeholder="Your Name"
            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
          <input type="email" name="email" placeholder="Your Email"
            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
          <textarea name="message" rows="5" placeholder="Your Message"
            required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
          <button type="submit">Send Message</button>
        </form>

        <div class="contact-info">
          <h2>Reach Us</h2>
          <p><strong>Email:</strong> support@soundex.com</p>
          <p><strong>Phone:</strong> +91 98765 43210</p>
          <p><strong>Location:</strong> Jamnagar, Gujarat, India</p>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="footer-content">
      <div class="footer-logo">
        <h2>Soun<span>Dex</span></h2>
        <p>Your one-stop shop for premium audio.</p>
      </div>
      <div class="footer-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="../pages/home.php">Home</a></li>
          <li><a href="../pages/buy.php">Shop</a></li>
          <li><a href="../pages/services.php">Services</a></li>
          <li><a href="../pages/contact us.php">Contact</a></li>
        </ul>
      </div>
      <div class="footer-social">
        <h3>Follow Us</h3>
        <div class="social-icons">
          <a href="#" class="social-icon">FB</a>
          <a href="#" class="social-icon">IG</a>
          <a href="#" class="social-icon">TW</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> Soundex Audio Solutions. All rights reserved.</p>
    </div>
  </footer>


</body>

</html>