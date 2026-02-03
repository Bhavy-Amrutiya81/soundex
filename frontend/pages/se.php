<?php
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';

// Initialize ProductManager
$productManager = new ProductManager($pdo);

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
            'name' => 'Awesome Product',
            'description' => 'This is an awesome product that you can buy today. It has excellent features and is a must-have!',
            'price' => 2999,
            'image_url' => 'product-image.jpg'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name'] ?? 'Product Purchase Page'); ?></title>
    <link rel="stylesheet" href="../CSS/gallary.css">
    <style>
        /* Add specific styles for this page if needed */
        body {
            margin: 0;
            padding: 0;
            background-color: beige;
            font-family: Arial, sans-serif;
        }
        
        .product-section {
            padding: 120px 20px 40px 20px;
            min-height: calc(100vh - 160px);
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
        </ul>
    </nav>

    <section class="product-section">
        <div class="product-container">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'product-image.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? 'Product Image'); ?>" onerror="this.onerror=null; this.src='../../assets/images/product_gallery/default.jpg';">
            </div>
            <div class="product-details">
                <h2><?php echo htmlspecialchars($product['name'] ?? 'Awesome Product'); ?></h2>
                <p><?php echo htmlspecialchars($product['description'] ?? 'This is an awesome product that you can buy today. It has excellent features and is a must-have!'); ?></p>
                <p class="price">₹<?php echo number_format($product['price'] ?? 0); ?></p>
                <button class="buy-btn" onclick="buyNow('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>)">Buy Now</button>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <p>&copy; 2025 Online Store. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
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