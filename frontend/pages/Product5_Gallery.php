<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';

// Initialize ProductManager
$productManager = new ProductManager($pdo);

// Get product ID from URL parameter
$productId = $_GET['id'] ?? 5;

// Get specific product
$product = $productManager->getProductById($productId);

// If no specific product was found, use a default product
if (!$product) {
    $products = $productManager->getAllProducts();
    $product = !empty($products) ? $products[4] : null;
    
    if (!$product) {
        // Default product if no products exist in the database
        $product = [
            'id' => 5,
            'name' => 'boAt Stone 1200',
            'description' => 'High-performance speaker with 14W stereo sound and RGB lights.',
            'price' => 3999,
            'image_url' => '/Bhavya/assets/images/product_gallery/35.jpg',
            'features' => [
                '14W stereo sound',
                'RGB lighting',
                'Bluetooth 5.0',
                'TWS (True Wireless Stereo)'
            ]
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['name'] ?? 'Product Detail'); ?> - Soundex</title>
  <link rel="stylesheet" href="../CSS/header.css" />
  
  <style>
  * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, Helvetica, sans-serif;
}

body {
  background:beige;
  padding: 20px;
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
      <li><a href="../pages/Gallery.php" class="active">Gallery</a></li>
      <li><a href="../pages/faqs.php">FAQs</a></li>
      <li><a href="../pages/services.php">Services</a></li>
      <li><a href="../pages/contact us.php">Contact</a></li>
      <li><a href="../pages/about.php">About</a></li>
    </ul>
  </nav>

  <!-- Main Content with proper spacing for fixed header -->
  <div style="padding: 120px 20px 40px 20px; min-height: calc(100vh - 160px);">
    <div class="container">
    <!-- Product Images -->
    <div class="product-images">
      <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/35.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>" class="main-img" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
      <div class="thumbnail">
        <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/35.jpg'); ?>" alt="thumb1" onclick="changeImage(this.src)" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
        <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/35.jpg'); ?>" alt="thumb2" onclick="changeImage(this.src)" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
        <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/35.jpg'); ?>" alt="thumb3" onclick="changeImage(this.src)" onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
      </div>
    </div>

    <!-- Product Details -->
    <div class="product-details">
      <h1><?php echo htmlspecialchars($product['name'] ?? 'Wireless Bluetooth Headphones'); ?></h1>
      <p class="price">₹<?php echo number_format($product['price'] ?? 0); ?></p>
      <p class="description">
        <?php echo htmlspecialchars($product['description'] ?? 'High-performance speaker with 14W stereo sound and RGB lights.'); ?>
      </p>

      <ul class="features">
        <?php if (!empty($product['features'])): ?>
          <?php foreach ($product['features'] as $feature): ?>
            <li>✅ <?php echo htmlspecialchars($feature); ?></li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>✅ 14W stereo sound</li>
          <li>✅ RGB lighting</li>
          <li>✅ Bluetooth 5.0</li>
          <li>✅ TWS (True Wireless Stereo)</li>
        <?php endif; ?>
      </ul>
      
      <div class="buttons">
        <button class="cart" onclick="addToCart('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>)">Add to Cart</button>
        <button class="buy" onclick="buyNow('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>)">Buy Now</button>
      </div>
    </div>
  </div>
</div>

<script>
function changeImage(src) {
  document.querySelector('.main-img').src = src;
}

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
}
</script>
</body>
</html>