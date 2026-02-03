<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';
require_once '../../backend/php/UserManager.php';

// Initialize ProductManager and UserManager
$productManager = new ProductManager($pdo);
$userManager = new UserManager($pdo);

// Get all products
$products = $productManager->getAllProducts();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Soundex Gallery - Premium Speakers & Audio Equipment</title>
  <link rel="stylesheet" href="/Bhavya/frontend/css/gallary.css" />
  <link rel="stylesheet" href="/Bhavya/frontend/css/header.css" />
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      color: #333;
      padding-top: 80px;
      /* Account for fixed header */
    }

    .main-content {
      padding: 20px 0;
    }

    .gallery-header {
      text-align: center;
      margin-bottom: 40px;
      padding: 20px;
    }

    .gallery-header h1 {
      font-size: 2.8rem;
      color: #1a1a2e;
      margin-bottom: 15px;
      letter-spacing: 1px;
      position: relative;
      display: inline-block;
    }

    .gallery-header h1::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(90deg, #3498db, #2980b9);
      border-radius: 2px;
    }

    .gallery-header p {
      font-size: 1.2rem;
      color: #666;
      max-width: 600px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 30px;
      padding: 0 20px;
      max-width: 1400px;
      margin: 0 auto;
    }

    .product-card {
      background: #ffffff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      border: 1px solid #eef2f7;
    }

    .product-card:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .product-image-container {
      position: relative;
      height: 220px;
      overflow: hidden;
      background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    }

    .product-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
      transform: scale(1.1);
    }

    .product-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
    }

    .product-info {
      padding: 25px;
    }

    .product-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 12px;
      line-height: 1.4;
    }

    .product-description {
      color: #666;
      font-size: 0.95rem;
      margin-bottom: 18px;
      line-height: 1.5;
    }

    .product-price {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .price-current {
      font-size: 1.5rem;
      font-weight: 800;
      color: #27ae60;
    }

    .price-original {
      font-size: 1.1rem;
      color: #999;
      text-decoration: line-through;
      margin-left: 10px;
    }

    .product-actions {
      display: flex;
      gap: 12px;
    }

    .btn-action {
      flex: 1;
      padding: 12px 0;
      border: none;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
    }

    .btn-view {
      background: linear-gradient(135deg, #3498db, #2980b9);
      color: white;
    }

    .btn-cart {
      background: linear-gradient(135deg, #27ae60, #219653);
      color: white;
    }

    .btn-action:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .rating {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .stars {
      color: #f39c12;
      margin-right: 10px;
    }

    .rating-text {
      color: #666;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        padding: 0 15px;
      }

      .gallery-header h1 {
        font-size: 2.2rem;
      }

      .product-actions {
        flex-direction: column;
      }

      .btn-action {
        width: 100%;
      }
    }

    @media (max-width: 480px) {
      .products-grid {
        grid-template-columns: 1fr;
      }

      .gallery-header h1 {
        font-size: 1.8rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="/Bhavya/frontend/pages/about.php">
          <h1>Soun<p>Dex</p>
          </h1>
        </a></div>
      <li><a href="/Bhavya/frontend/pages/home.php">Home</a></li>
      <li><a href="/Bhavya/frontend/pages/Gallery.php" class="active">Gallery</a></li>
      <li><a href="/Bhavya/frontend/pages/faqs.php">FAQs</a></li>
      <li><a href="/Bhavya/frontend/pages/services.php">Services</a></li>
      <li><a href="/Bhavya/frontend/pages/contact%20us.php">Contact</a></li>
      <li><a href="/Bhavya/frontend/pages/about.php">About</a></li>
      <?php if ($isLoggedIn): ?>
        <li><a href="/Bhavya/frontend/pages/history.php">History</a></li>
        <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a></li>
        <li><a href="/Bhavya/frontend/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="/Bhavya/frontend/pages/login.php">Login</a></li>
        <li><a href="/Bhavya/frontend/pages/signup.php">Sign Up</a></li>
      <?php endif; ?>
      <li><a href="/Bhavya/frontend/pages/checkout.php" class="cart-icon" id="cartIcon">
          🛒
          <span class="cart-count" id="cartCount">0</span>
        </a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="gallery-header">
      <h1>Premium Audio Collection</h1>
      <p>Discover our curated selection of premium speakers and audio equipment. Each product is carefully chosen for
        exceptional sound quality and durability.</p>
    </div>

    <div class="products-grid">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <div class="product-image-container">
              <img
                src="<?php echo htmlspecialchars($product['image_url'] ?? '../../assets/images/product_gallery/1.jpg'); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image"
                onerror="this.onerror=null; this.src='../../assets/images/product_gallery/1.jpg';">
              <?php if (isset($product['is_featured']) && $product['is_featured']): ?>
                <div class="product-badge">Featured</div>
              <?php elseif (isset($product['is_best_seller']) && $product['is_best_seller']): ?>
                <div class="product-badge">Best Seller</div>
              <?php elseif (isset($product['is_on_sale']) && $product['is_on_sale']): ?>
                <div class="product-badge">Sale</div>
              <?php endif; ?>
            </div>
            <div class="product-info">
              <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
              <div class="rating">
                <div class="stars">★★★★★</div>
                <span class="rating-text">(<?php echo rand(50, 300); ?> Reviews)</span>
              </div>
              <p class="product-description">
                <?php echo htmlspecialchars($product['description'] ?? 'Premium audio equipment with superior sound quality.'); ?>
              </p>
              <div class="product-price">
                <span class="price-current">₹<?php echo number_format($product['price'] ?? 0); ?></span>
                <?php if (isset($product['original_price']) && $product['original_price'] > $product['price']): ?>
                  <span class="price-original">₹<?php echo number_format($product['original_price']); ?></span>
                <?php endif; ?>
              </div>
              <div class="product-actions">
                <a href="product_detail.php" class="btn-action btn-view">View Details</a>
                <button class="btn-action btn-cart"
                  onclick="addToCartAndCheckout('<?php echo addslashes(htmlspecialchars($product['name'])); ?>', <?php echo $product['price'] ?? 0; ?>)">Buy
                  Now</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-products">
          <h3>No products available at the moment.</h3>
          <p>Please check back later for new arrivals.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    function addToCart(productName, price) {
      // Get existing cart or create new one
      let cart = JSON.parse(localStorage.getItem('cart')) || [];

      // Check if product already exists in cart
      const existingItemIndex = cart.findIndex(item => item.name === productName);

      if (existingItemIndex > -1) {
        // Increase quantity if item already exists
        cart[existingItemIndex].quantity += 1;
      } else {
        // Add new item to cart
        cart.push({
          name: productName,
          price: price,
          quantity: 1
        });
      }

      // Save cart to localStorage
      localStorage.setItem('cart', JSON.stringify(cart));

      // Show confirmation message
      alert(`${productName} added to cart!`);
    }

    function buyNow(productName, price) {
      // Check if user is logged in
      <?php if (!$isLoggedIn): ?>
        // Show login/signup prompt
        if (confirm('You need to login or signup to proceed with checkout. Would you like to login now?')) {
          window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname);
        }
        return;
      <?php else: ?>
        // Clear existing cart and add only this product
        const cart = [{
          name: productName,
          price: price,
          quantity: 1
        }];

        // Save to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Redirect to checkout
        window.location.href = 'checkout.php';
      <?php endif; ?>
    }

    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Update cart count on page load
    function updateCartCount() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const totalItems = cart.reduce((total, item) => total + (item.quantity || 1), 0);
      const cartCountElement = document.getElementById('cartCount');
      const cartIconElement = document.getElementById('cartIcon');

      if (cartCountElement) {
        cartCountElement.textContent = totalItems;

        // Add/remove empty class based on cart status
        if (totalItems > 0) {
          cartIconElement.classList.remove('empty');
        } else {
          cartIconElement.classList.add('empty');
        }
      }
    }

    // Update cart count initially and after any cart changes
    updateCartCount();

    // Override addToCart to also update the cart count display
    const originalAddToCart = addToCart;
    window.addToCart = function (productName, price) {
      originalAddToCart(productName, price);
      updateCartCount();
    };

    // Enhanced function to handle adding to cart and then proceeding to checkout
    function addToCartAndCheckout(productName, price) {
      // Check if user is logged in
      <?php if (!$isLoggedIn): ?>
        // Show login/signup prompt
        if (confirm('You need to login or signup to proceed with checkout. Would you like to login now?')) {
          window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname);
        }
        return;
      <?php else: ?>
        // Add to cart and redirect to checkout
        addToCart(productName, price);
        window.location.href = 'checkout.php';
      <?php endif; ?>
    }
  </script>
</body>

</html>