<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';
require_once '../../backend/php/UserManager.php';

// Initialize ProductManager and UserManager
$productManager = new ProductManager($pdo);
$userManager = new UserManager($pdo);

// Get product ID from URL or default to a specific product
$productId = $_GET['id'] ?? 1; // Default to first product if no ID provided

// Fetch product details from database
$product = $productManager->getProductById($productId);

// If no specific product was requested or product not found, show a default product or redirect
if (!$product) {
    // If we couldn't find a specific product, we'll use a default approach
    $products = $productManager->getAllProducts();
    $product = !empty($products) ? $products[0] : null;
    
    if (!$product) {
        // If no products exist in the database, use default values
        $product = [
            'id' => 1,
            'name' => 'Sony SRS-XB33 Extra Bass',
            'description' => 'Experience powerful, EXTRA BASS from this compact, waterproof speaker. The passive radiator works together with the monaural speaker to reproduce deep, punchy bass. With Party Connect, you can play music on multiple compatible devices at the same time.',
            'price' => 15999,
            'image_url' => '/Bhavya/assets/images/product_gallery/32.jpg',
            'features' => [
                'Waterproof & Dustproof (IP67 rated)',
                'Up to 24 hours battery life',
                'Extra Bass technology for powerful low-end',
                'Party Connect - connect up to 100 speakers',
                'Built-in mic for speakerphone calls',
                '360° sound distribution'
            ]
        ];
    }
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['name'] ?? 'Product Detail'); ?> - Soundex</title>
  <link rel="stylesheet" href="/Bhavya/frontend/css/gallary.css">
  <link rel="stylesheet" href="/Bhavya/frontend/css/header.css">
  <style>
    /* Reuse existing CSS classes from gallary.css and header.css */
    body {
      background-color: #f5f5f0;
      font-family: Arial, sans-serif;
      color: #1a1a2e;
      padding-top: 80px; /* Account for fixed header */
    }

    .main-content {
      padding-top: 20px; /* Additional spacing below header */
    }

    .product-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .product-content {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      padding: 20px 0;
    }

    .product-images-section {
      flex: 1;
      min-width: 300px;
      text-align: center;
    }

    .main-product-img {
      width: 100%;
      max-width: 400px;
      height: auto;
      border-radius: 12px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
      margin-bottom: 20px;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: 1px solid #e0e0e0;
      transform: perspective(1000px) rotateY(0deg);
      position: relative;
    }
    
    .main-product-img:hover {
      transform: perspective(1000px) rotateY(-5deg) scale(1.03);
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
    }
    
    .image-container {
      position: relative;
      display: inline-block;
      padding: 15px;
      background: linear-gradient(145deg, #f0f0f0, #ffffff);
      border-radius: 16px;
      box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.08), 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .image-container::after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 10%;
      width: 80%;
      height: 30px;
      background: radial-gradient(ellipse at center, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0) 70%);
      border-radius: 50%;
      opacity: 0.4;
    }

    .thumbnail-container {
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .thumbnail-img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      border: 2px solid #ddd;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .thumbnail-img:hover {
      border-color: #3498db;
      transform: scale(1.05) translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    
    .thumbnail-img.active {
      border-color: #e74c3c;
      box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.3);
    }

    .product-details-section {
      flex: 1;
      min-width: 300px;
      padding: 20px 0;
    }

    .product-title {
      font-size: 28px;
      margin-bottom: 15px;
      color: #1a1a2e;
    }

    .product-price {
      font-size: 24px;
      color: #c9a94d;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .product-description {
      color: #555;
      margin-bottom: 25px;
      line-height: 1.7;
    }

    .product-features {
      list-style: none;
      margin: 25px 0;
      padding-left: 0;
    }

    .feature-item {
      padding: 10px 0;
      border-bottom: 1px solid #eee;
      display: flex;
      align-items: center;
    }

    .feature-item:last-child {
      border-bottom: none;
    }

    .feature-icon {
      color: #27ae60;
      margin-right: 10px;
      font-weight: bold;
    }

    .action-buttons {
      margin-top: 30px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .btn {
      padding: 14px 28px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-cart {
      background-color: #3498db;
      color: white;
      flex: 1;
      min-width: 150px;
    }

    .btn-buy {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      color: white;
      flex: 1;
      min-width: 150px;
    }

    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .btn-cart:hover {
      background-color: #2980b9;
    }

    .btn-buy:hover {
      background: linear-gradient(135deg, #c0392b, #a5281b);
    }

    @media (max-width: 768px) {
      .product-content {
        flex-direction: column;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="/Bhavya/frontend/pages/about.php"><h1>Soun<p>Dex</p></h1></a></div>
      <li><a href="/Bhavya/frontend/pages/home.php">Home</a></li>
      <li><a href="/Bhavya/frontend/pages/Gallery.php" class="active">Gallery</a></li>
      <li><a href="/Bhavya/frontend/pages/faqs.php">FAQs</a></li>
      <li><a href="/Bhavya/frontend/pages/services.php">Services</a></li>
      <li><a href="/Bhavya/frontend/pages/contact%20us.php">Contact</a></li>
      <li><a href="/Bhavya/frontend/pages/about.php">About</a></li>
      <?php if ($isLoggedIn): ?>
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

  <!-- Main Content with proper spacing for fixed header -->
  <div class="main-content">
    <div class="product-container">
      <div class="product-content">
        <!-- Product Images Section -->
        <div class="product-images-section">
          <div class="image-container">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/32.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?>" class="main-product-img" id="main-product-image" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
          </div>
          <div class="thumbnail-container">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/32.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?> front view" class="thumbnail-img active" onclick="changeImage(this.src, this)" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/32.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?> side view" class="thumbnail-img" onclick="changeImage(this.src, this)" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/32.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?> back view" class="thumbnail-img" onclick="changeImage(this.src, this)" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
          </div>
        </div>

        <!-- Product Details Section -->
        <div class="product-details-section">
          <h1 class="product-title"><?php echo htmlspecialchars($product['name'] ?? 'Product Name'); ?></h1>
          <p class="product-price">₹<?php echo number_format($product['price'] ?? 0); ?></p>
          
          <p class="product-description">
            <?php echo htmlspecialchars($product['description'] ?? 'Product description not available.'); ?>
          </p>
          
          <ul class="product-features">
            <?php if (!empty($product['features'])): ?>
              <?php foreach ($product['features'] as $feature): ?>
                <li class="feature-item"><span class="feature-icon">✓</span> <?php echo htmlspecialchars($feature); ?></li>
              <?php endforeach; ?>
            <?php else: ?>
              <li class="feature-item"><span class="feature-icon">✓</span> Waterproof & Dustproof (IP67 rated)</li>
              <li class="feature-item"><span class="feature-icon">✓</span> Up to 24 hours battery life</li>
              <li class="feature-item"><span class="feature-icon">✓</span> Extra Bass technology for powerful low-end</li>
              <li class="feature-item"><span class="feature-icon">✓</span> Party Connect - connect up to 100 speakers</li>
              <li class="feature-item"><span class="feature-icon">✓</span> Built-in mic for speakerphone calls</li>
              <li class="feature-item"><span class="feature-icon">✓</span> 360° sound distribution</li>
            <?php endif; ?>
          </ul>
          
          <div class="action-buttons">
            <button class="btn btn-cart" onclick="addToCart('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>)">Add to Cart</button>
            <button class="btn btn-buy" onclick="addToCartAndCheckout('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>)">Buy Now</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function changeImage(src, element) {
      document.getElementById('main-product-image').src = src;
      
      // Remove active class from all thumbnails
      const thumbnails = document.querySelectorAll('.thumbnail-img');
      thumbnails.forEach(thumb => thumb.classList.remove('active'));
      
      // Add active class to clicked thumbnail
      element.classList.add('active');
    }
    
    // Set the first thumbnail as active by default
    document.addEventListener('DOMContentLoaded', function() {
      const firstThumbnail = document.querySelector('.thumbnail-img');
      if (firstThumbnail) {
        firstThumbnail.classList.add('active');
      }
    });
    
    // Add to cart function
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
    
    // Buy now function
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
        
        // Update cart count
        updateCartCount();
        
        // Redirect to checkout
        window.location.href = 'checkout.php';
      <?php endif; ?>
    }
    
    // Update cart count function
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
    
    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
      updateCartCount();
      
      const firstThumbnail = document.querySelector('.thumbnail-img');
      if (firstThumbnail) {
        firstThumbnail.classList.add('active');
      }
    });
    
    // Override addToCart to also update the cart count display
    const originalAddToCart = addToCart;
    window.addToCart = function(productName, price) {
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