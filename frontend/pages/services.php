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
  <title>Our Services – Soundex</title>
  <link rel="stylesheet" href="../CSS/services.css">
  <link rel="stylesheet" href="../CSS/header.css" />
</head>
<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="../pages/about.php"><h1>Soun<p>Dex</p></h1></a></div>
      <li><a href="../pages/home.php">Home</a></li>
      <li><a href="../pages/Gallery.php">Gallery</a></li>
      <li><a href="../pages/faqs.php">FAQs</a></li>
      <li><a href="../pages/services.php" class="active">Services</a></li>
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
  <section class="services-section">
    <h1>Our Services</h1>
    <p class="intro">Explore how Soundex empowers you to buy, sell, repair, exchange, and learn—all in one place.</p>

    <div class="services-grid">
      <div class="service-card" onclick="showDetails('buy')">
        <h2>Buy</h2>
        <p>Shop premium audio gear with verified quality and eco-friendly packaging.</p>
      </div>
      <div class="service-card" onclick="showDetails('sell')">
        <h2>Sell</h2>
        <p>List your used speakers and accessories with ease and transparency.</p>
      </div>
      <div class="service-card" onclick="showDetails('repair')">
        <h2>Repair</h2>
        <p>Get expert diagnostics and sustainable repair options for your devices.</p>
      </div>
      <div class="service-card" onclick="showDetails('exchange')">
        <h2>Exchange</h2>
        <p>Swap your gear for upgrades or alternatives—reduce waste, increase value.</p>
      </div>
      <div class="service-card" onclick="showDetails('learn')">
        <h2>Learn</h2>
        <p>Access tutorials, guides, and community insights to build your tech skills.</p>
      </div>
    </div>

    <div id="service-details" class="details-box"></div>
  </section>

  <script src="../js/services.js"></script>
</body>
</html>