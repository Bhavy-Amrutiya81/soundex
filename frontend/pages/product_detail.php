<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';
require_once '../../backend/php/UserManager.php';

// Initialize ProductManager and UserManager
$productManager = new ProductManager($pdo);
$userManager = new UserManager($pdo);

// Get product ID from URL parameter
$productId = $_GET['id'] ?? 1;

// Get specific product
$product = $productManager->getProductById($productId);

// If no specific product was found, use a default product
if (!$product) {
    $products = $productManager->getAllProducts();
    $product = !empty($products) ? $products[0] : null;
    
    if (!$product) {
        // Default product if no products exist in the database
        $product = [
            'id' => 1,
            'name' => 'Wireless Bluetooth Headphones',
            'description' => 'Experience crystal-clear sound and long-lasting comfort with these wireless Bluetooth headphones. Perfect for music, calls, and gaming.',
            'price' => 4999,
            'image_url' => '../../assets/images/product_gallery/1.jpg',
            'features' => [
                'Bluetooth 5.0 connectivity',
                '20 hours battery life',
                'Noise-cancelling microphone',
                'Lightweight & foldable design'
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
  
  <style>
  * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, Helvetica, sans-serif;
}

body {
  background: beige;
  padding: 0;
  margin: 0;
}

/* Navigation Header */
nav {
  background-color: #fff;
  overflow: hidden;
  position: fixed;
  display: flex;
  z-index: 2;
  width: 100%;
  top: 0;
}

nav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: right;
}

nav ul li a {
  color: black;
  padding: 14px 30px;
  text-align: center;
  text-transform: uppercase;
  text-decoration: none;
  font-weight: bolder;
  font-size: 90%;
  position: relative;
  transition: all 0.3s ease;
}

nav ul li a:hover {
  color: #3498db;
}

li a::before {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 50%;
  width: 0;
  height: 7px;
  background-color: steelblue;
  transition: all 0.5s;
  transform: translateX(-50%);
}

li a:hover::before {
  width: 70%;
}

.logo h1 {
  color: black;
  display: flex;
  padding: 20px 40px;
  text-transform: uppercase;
  font-family: 'Times New Roman', Times, serif;
  font-size: 30px;
}

.logo a {
  text-decoration: none;
}

.logo p {
  color: cornflowerblue;
  font-weight: 600;
  font-family: 'Times New Roman', Times, serif;
}

.container {
  display: block;
  flex-wrap: wrap;
  background: #fff;
  padding: 140px 30px 30px 30px; /* Added top padding for fixed header */
  border-radius: 10px;
  max-width: auto;
  margin: auto;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  min-height: calc(100vh - 160px);
}

.container {
  display:block;
  flex-wrap: wrap;
  background: #fff;
  padding:0 0 0 30px;
  border-radius: 10px;
  max-width: auto;
  margin: auto;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.product-images {
  flex: 1;
  min-width: 300px;
  text-align: center;
}

.main-img {
  width: 100%;
  max-width: 400px;
  
  
  border-radius: 10px;
}

.thumbnail {
  margin-top: 10px;
}

.thumbnail img {
  width: 80px;
  margin: 5px;
  border: 2px solid #ddd;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.thumbnail img:hover {
  border-color: #ff9900;
}

.product-details {
  flex: 1;
  min-width: 300px;
  padding: 20px;
}

.product-details h1 {
  font-size: 28px;
  margin-bottom: 10px;
}

.price {
  color: #b12704;
  font-size: 24px;
  margin-bottom: 15px;
  font-weight: bold;
}

.description {
  color: #333;
  margin-bottom: 20px;
  line-height: 1.6;
}

.buttons {
  margin-bottom: 20px;
}

button {
  padding: 12px 25px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: 0.3s;
}

.cart {
  background: #ffa41c;
  color: #ffffff;
}

.buy {
  background:#ffa41c;
  color: white;

}

button:hover {
  background-color: #058509;
  
}

.features {
  list-style: none;
  margin-top: 20px;
}

.features li {
  margin-bottom: 30px;
  color: #555;
  font-size: 120%;


}
  </style>
</head>
<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="../pages/about.php"><h1>Soun<p>Dex</p></h1></a></div>
      <li><a href="../pages/home.php">Home</a></li>
      <li><a href="../pages/Gallery.php">Gallery</a></li>
      <li><a href="../pages/faqs.php">FAQs</a></li>
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

  <div class="container">
    <!-- Product Images -->
    <div class="product-images">
      <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>" class="main-img" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
      <div class="thumbnail">
        <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>" alt="thumb1" onclick="changeImage(this.src)" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
        <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>" alt="thumb2" onclick="changeImage(this.src)" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
        <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>" alt="thumb3" onclick="changeImage(this.src)" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
      </div>
    </div>

    <!-- Product Details -->
    <div class="product-details">
      <h1><?php echo htmlspecialchars($product['name'] ?? 'Wireless Bluetooth Headphones'); ?></h1>
      <p class="price">₹<?php echo number_format($product['price'] ?? 0); ?></p>
      <p class="description">
        <?php echo htmlspecialchars($product['description'] ?? 'Experience crystal-clear sound and long-lasting comfort with these wireless Bluetooth headphones. Perfect for music, calls, and gaming.'); ?>
      </p>

      <ul class="features">
        <?php if (!empty($product['features'])): ?>
          <?php foreach ($product['features'] as $feature): ?>
            <li>✅ <?php echo htmlspecialchars($feature); ?></li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>✅ Bluetooth 5.0 connectivity</li>
          <li>✅ 20 hours battery life</li>
          <li>✅ Noise-cancelling microphone</li>
          <li>✅ Lightweight & foldable design</li>
        <?php endif; ?>
      </ul>
      
      <div class="buttons">
        <button class="cart" onclick="addToCart('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>)">Add to Cart</button>
        <button class="buy" onclick="addToCartAndCheckout('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>)">Buy Now</button>
      </div>
    </div>
  </div>

  <script>
    function changeImage(src) {
      document.querySelector('.main-img').src = src;
    }
    
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
  </script>
</body>
</html>