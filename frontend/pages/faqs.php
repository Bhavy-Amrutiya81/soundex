<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

// Initialize UserManager
$userManager = new UserManager($pdo);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FAQs – Soundex</title>
  <link rel="stylesheet" href="../CSS/faqs.css">
  <link rel="stylesheet" href="../CSS/header.css" />
</head>
<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="../pages/about.php"><h1>Soun<p>Dex</p></h1></a></div>
      <li><a href="../pages/home.php">Home</a></li>
      <li><a href="../pages/Gallery.php">Gallery</a></li>
      <li><a href="../pages/faqs.php" class="active">FAQs</a></li>
      <li><a href="../pages/services.php">Services</a></li>
      <li><a href="../pages/contact us.php">Contact</a></li>
      <li><a href="../pages/about.php">About</a></li>
      <?php if ($isLoggedIn): ?>
      <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a></li>
      <li><a href="../logout.php">Logout</a></li>
      <?php else: ?>
      <li><a href="../pages/login.php">Login</a></li>
      <li><a href="../pages/signup.php">Sign Up</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <section class="faq-section">
    <h1>Frequently Asked Questions</h1>
    <p class="intro">Got questions? We've got answers. Tap to expand each topic.</p>

    <div class="faq-container">
      <div class="faq-item">
        <div class="faq-question">How do I buy a speaker on Soundex?</div>
        <div class="faq-answer">Browse our store, select your product, and proceed to checkout with secure payment options.</div>
      </div>
      <div class="faq-item">
        <div class="faq-question">Can I sell used audio gear?</div>
        <div class="faq-answer">Yes! Create a seller account, upload product details, and list your item for sale.</div>
      </div>
      <div class="faq-item">
        <div class="faq-question">Do you offer repair services?</div>
        <div class="faq-answer">Absolutely. Our technicians provide diagnostics and eco-friendly repairs for most audio devices.</div>
      </div>
      <div class="faq-item">
        <div class="faq-question">Is there a warranty on purchases?</div>
        <div class="faq-answer">All new products come with a 1-year warranty. Refurbished items include a 6-month coverage.</div>
      </div>
    </div>
  </section>

  <script src="../js/faqs.js"></script>
</body>
</html>