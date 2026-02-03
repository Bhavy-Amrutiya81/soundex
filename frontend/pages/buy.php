<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';
require_once '../../backend/php/UserManager.php';

// Initialize ProductManager and UserManager
$productManager = new ProductManager($pdo);
$userManager = new UserManager($pdo);

// Get all products from the database
$products = $productManager->getAllProducts();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Products - Soundex</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <style>
          .main-content {
        padding-top: 100px;
        min-height: calc(100vh - 140px);
    }
      /*.navbar{
        background-color: white;
        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        
        
        position: fixed;
        width: 100%;
        z-index: 2;
      }
      .navdiv{
        display: flex;
        align-items: center;
        justify-content:space-between ;
      }
      .logo{
        font-size: 30px;
        color:#fff;
        font-weight: 600;
      }
      li{
        list-style: none;
        display:inline-block;
    
      }
      li a{
        color:black;
        font-size:20px;
        font-weight: bold;
        margin-right: 20px;
        text-decoration:none;
      }*/
      
    
    .product-section {
        padding: 40px 20px;
        text-align: center;
    }
    
    .product-container {
        display: flex;
        justify-content: center;
        align-items: center;
        
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .product-image img {
        width: 300px;
        height: 300px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .product-details {
        margin-left: 20px;
        text-align: left;
    }
    
    .product-details h2 {
        font-size: 28px;
        margin-bottom: 15px;
    }
    
    .product-details p {
        font-size: 18px;
        margin-bottom: 20px;
    }
    
    .price {
        font-size: 22px;
        font-weight: bold;
        color: #ff5722;
        margin-bottom: 20px;
    }
    
    .buy-btn {
        background-color: #ff5722;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s;
        
    }
    
    .buy-btn:hover {
    background-color: #218838;
    transition: ease-in-out;
    

}
    
    .product-section {
        padding: 40px 20px;
        text-align: center;
    }
    
    .product-container {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    
    .product-image img {
        width: 300px;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .product-image img:hover {
        transform: scale(1.03);
    }
    
    .product-details {
        margin-left: 30px;
        text-align: left;
        max-width: 500px;
    }
    
    .product-details h2 {
        font-size: 32px;
        margin-bottom: 15px;
        color: #2c3e50;
    }
    
    .product-details p {
        font-size: 18px;
        margin-bottom: 20px;
        color: #7f8c8d;
        line-height: 1.6;
    }
    
    .price {
        font-size: 26px;
        font-weight: bold;
        color: #e74c3c;
        margin-bottom: 25px;
        background-color: #f8f9fa;
        display: inline-block;
        padding: 8px 15px;
        border-radius: 30px;
    }
    
    .buy-btn {
        background: linear-gradient(to right, #3498db, #2c3e50);
        color: white;
        padding: 14px 28px;
        border: none;
        border-radius: 30px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }
    
    .buy-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        background: linear-gradient(to right, #2980b9, #1a252f);
    }
    
    .buy-btn:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
        background: linear-gradient(to right, #1f618d, #151e27);
    }
    
    .buy-btn.clicked {
        animation: pulse 0.6s ease-in-out;
        background: linear-gradient(to right, #27ae60, #219653);
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); box-shadow: 0 8px 16px rgba(39, 174, 96, 0.4); }
        100% { transform: scale(1); }
    }
    
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: none;
        border-radius: 10px;
        width: 80%;
        max-width: 500px;
        position: relative;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {transform: translateY(-50px); opacity: 0;}
        to {transform: translateY(0); opacity: 1;}
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }
    
    .close:hover,
    .close:focus {
        color: #000;
    }
    
    .modal-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    
    .modal-body {
        margin: 20px 0;
    }
    
    .modal-footer {
        border-top: 1px solid #eee;
        padding-top: 15px;
        text-align: right;
    }
    
    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
    }
</style>
</head>
<body>
    <!-- Fixed Navigation Header -->
    <nav>
        
        
        <ul>
           <div class="logo"><a href="../pages/about.php"><h1>Soun<p>Dex</p></h1></a></div>
         
           
         <li><a href="../pages/home.php">Home</a></li>
         <li><a href="../pages/Gallery.php">Gallery</a></li>
         <li><a href="../pages/faqs.php">FAQs</a></li>
         <li><a href="../pages/services.php">Services</a></li>
         <li><a href="../pages/contact us.php">Contact</a></li>
         <li><a href="../pages/about.php">About</a></li>
         <li><a href="../pages/checkout.php" class="cart-icon" id="cartIcon">
           🛒
           <span class="cart-count" id="cartCount">0</span>
         </a></li>
        </ul>   
   </nav>
    
    <!-- Main Content Container -->
    <main class="main-content">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <a href="product_detail.php?id=<?php echo $product['id']; ?>"><img src="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';"></a>
                </div>
                <div class="product-details">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p><?php echo htmlspecialchars(substr($product['description'] ?? 'This is an awesome product that you can buy today. It has excellent features and is a must-have!', 0, 100)); ?>...</p>
                    <p class="price">₹<?php echo number_format($product['price'] ?? 0); ?></p>
                    <button class="buy-btn" data-product-name="<?php echo addslashes(htmlspecialchars($product['name'])); ?>" data-product-price="<?php echo $product['price'] ?? 0; ?>">Buy Now</button>
                </div>
            </div>
        </section>
        <?php endforeach; ?>
    <?php else: ?>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <a href="product_detail.php"><img src="../assets/images/product_gallery/1.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';"></a>
                </div>
                <div class="product-details">
                    <h2>Bose SoundLink Micro speaker</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹2999</p>
                    <button class="buy-btn" data-product-name="Bose SoundLink Micro speaker" data-product-price="2999">Buy Now</button>
                </div>
            </div>
        </section>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <img src="../assets/images/product_gallery/2.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                </div>
                <div class="product-details">
                    <h2>Soundcore Motion 300</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹3999</p>
                    <button class="buy-btn" data-product-name="Soundcore Motion 300" data-product-price="3999">Buy Now</button>
                </div>
            </div>
        </section>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <img src="../assets/images/product_gallery/3.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                </div>
                <div class="product-details">
                    <h2> Denon Envaya DSB-250BT</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹4999</p>
                    <button class="buy-btn" data-product-name="Denon Envaya DSB-250BT" data-product-price="4999">Buy Now</button>
                </div>
            </div>
        </section>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <img src="../assets/images/product_gallery/4.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                </div>
                <div class="product-details">
                    <h2>Marshall MIDDLETON</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹8999</p>
                    <button class="buy-btn" data-product-name="Marshall MIDDLETON" data-product-price="8999">Buy Now</button>
                </div>
            </div>
        </section>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <img src="../assets/images/product_gallery/5.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                </div>
                <div class="product-details">
                    <h2>Marshall Acton Bluetooth speaker</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹12999</p>
                    <button class="buy-btn" data-product-name="Marshall Acton Bluetooth speaker" data-product-price="12999">Buy Now</button>
                </div>
            </div>
        </section>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <img src="../assets/images/product_gallery/23.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                </div>
                <div class="product-details">
                    <h2>JBL Clip-4</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹1999</p>
                    <button class="buy-btn" data-product-name="JBL Clip-4" data-product-price="1999">Buy Now</button>
                </div>
            </div>
        </section>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <img src="../assets/images/product_gallery/7.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                </div>
                <div class="product-details">
                    <h2>Sony PUMPX (SRS-X2)</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹2499</p>
                    <button class="buy-btn" data-product-name="Sony PUMPX (SRS-X2)" data-product-price="2499">Buy Now</button>
                </div>
            </div>
        </section>
        <section class="product-section">
            <div class="product-container">
                <div class="product-image">
                    <img src="../assets/images/product_gallery/34.jpg" alt="Product Image" onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                </div>
                <div class="product-details">
                    <h2> pTron Fusion Go 10W</h2>
                    <p>This is an awesome product that you can buy today. It has excellent features and is a must-have!</p>
                    <p class="price">₹1499</p>
                    <button class="buy-btn" data-product-name="pTron Fusion Go 10W" data-product-price="1499">Buy Now</button>
                </div>
            </div>
        </section>
    <?php endif; ?>
    </main>
    <!-- Modal Structure -->
    <div id="purchaseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Purchase Information</h2>
            </div>
            <div class="modal-body">
                <h3 id="productName"></h3>
                <p id="productPrice"></p>
                <p id="productDescription"></p>
                <p><strong>Next Steps:</strong></p>
                <ol>
                    <li>You will be redirected to our secure checkout page</li>
                    <li>Fill in your shipping and payment details</li>
                    <li>Review your order and confirm purchase</li>
                    <li>You will receive an order confirmation email</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="cancelBtn">Cancel</button>
                <button class="btn-primary" id="proceedBtn">Proceed to Checkout</button>
            </div>
        </div>
    </div>
    
    <script>
        // Get DOM elements
        const modal = document.getElementById('purchaseModal');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.getElementById('cancelBtn');
        const proceedBtn = document.getElementById('proceedBtn');
        const modalTitle = document.getElementById('modalTitle');
        const productName = document.getElementById('productName');
        const productPrice = document.getElementById('productPrice');
        const productDescription = document.getElementById('productDescription');
        
        // Get all buy buttons
        const buyButtons = document.querySelectorAll('.buy-btn');
        
        // Add event listeners to all buy buttons
        buyButtons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Add attractive click animation
                this.classList.add('clicked');
                
                // Remove animation class after it completes
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 600);
                
                // Get product information from the button's data attributes
                const title = this.getAttribute('data-product-name');
                const price = '₹' + this.getAttribute('data-product-price');
                const description = 'This is an awesome product that you can buy today. It has excellent features and is a must-have!';
                
                // Update modal content
                productName.textContent = title;
                productPrice.innerHTML = '<strong>Price: </strong>' + price;
                productDescription.innerHTML = '<strong>Description: </strong>' + description;
                
                // Show modal after brief delay for animation
                setTimeout(() => {
                    modal.style.display = 'block';
                }, 300);
            });
        });
        
        // Close modal functions
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        
        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Close modal function
        function closeModal() {
            modal.style.display = 'none';
        }
        
        // Proceed to checkout
        proceedBtn.addEventListener('click', function() {
            // Check if user is logged in
            <?php if (!$isLoggedIn): ?>
                // Show login/signup prompt
                if (confirm('You need to login or signup to proceed with checkout. Would you like to login now?')) {
                    window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname);
                }
                return;
            <?php else: ?>
                // Get product info from modal
                const productInfo = {
                    name: productName.textContent,
                    price: parseFloat(productPrice.textContent.replace(/[^0-9.-]+/g, "")),
                    quantity: 1
                };
                
                // Get existing cart or create new one
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                
                // Add product to cart
                cart.push(productInfo);
                
                // Save cart to localStorage
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Update cart count
                updateCartCount();
                
                // Redirect to checkout page
                window.location.href = 'checkout.php';
            <?php endif; ?>
        });
        
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
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>
</html>