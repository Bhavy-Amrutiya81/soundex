<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';

// Initialize ProductManager
$productManager = new ProductManager($pdo);

// Get featured products (limit to 2 for the home page)
$products = $productManager->getAllProducts();
if (count($products) > 2) {
    $products = array_slice($products, 0, 2); // Limit to first 2 products
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Soundex Audio Solutions</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/header.css" />
</head>

<body>

    <!-- Navigation Header -->
    <nav>
        <ul>
            <div class="logo"><a href="../pages/about.php">
                    <h1>Soun<p>Dex</p>
                    </h1>
                </a></div>
            <li><a href="../pages/home.php" class="active">Home</a></li>
            <li><a href="../pages/Gallery.php">Gallery</a></li>
            <li><a href="../pages/faqs.php">FAQs</a></li>
            <li><a href="../pages/services.php">Services</a></li>
            <li><a href="../pages/contact us.php">Contact</a></li>
            <li><a href="../pages/about.php">About</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="../pages/history.php">History</a></li>
                <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a>
                </li>
                <li><a href="../logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="../pages/login.php">Login</a></li>
                <li><a href="../pages/signup.php">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="main-content">
        <section class="hero-section">
            <h1>Welcome to Soundex</h1>
            <p>Your premier destination for audio solutions</p>
        </section>

        <section class="new-speakers">
            <h2>Shop Our Latest Audio Products</h2>
            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <a href="../pages/buy.php" class="product-card">
                            <div class="product">
                                <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/1.jpg'); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
                                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                                <p><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 100)); ?>...</p>
                                <p class="price">₹<?php echo number_format($product['price'] ?? 0); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Fallback content if no products from DB -->
                <div class="products-grid">
                    <a href="../pages/buy.php" class="product-card">
                        <div class="product">
                            <img src="/Bhavya/assets/images/product_gallery/1.jpg" alt="Premium Bluetooth Speaker"
                                onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
                            <h2>Bluetooth Speakers</h2>
                            <p>High-quality wireless audio solutions</p>
                        </div>
                    </a>
                    <a href="../pages/portable speaker.php" class="product-card">
                        <div class="product">
                            <img src="/Bhavya/assets/images/product_gallery/2.jpg" alt="Portable Speaker"
                                onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/2.jpg';">
                            <h2>Portable Speakers</h2>
                            <p>Take your music anywhere with our portable speakers</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <section class="services-section">
            <h2>Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Repair Services</h3>
                    <p>Professional repair for all audio devices</p>
                    <a href="../pages/repair.php" class="service-btn">Live Repair</a>
                </div>
                <div class="service-card">
                    <h3>Sell Your Devices</h3>
                    <p>Trade in your old audio equipment for great value</p>
                    <a href="../pages/sell.php" class="service-btn">Sell Now</a>
                </div>
            </div>
        </section>

        <section class="internship-section">
            <h2>Join Our Team</h2>
            <div class="internship-content">
                <p>Gain hands-on experience in audio repair and technology</p>
                <a href="../pages/internship2.php" class="internship-btn">Apply for Free Internship</a>
            </div>
        </section>
    </main>

</body>

</html>