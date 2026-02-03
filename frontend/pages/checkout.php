<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login with redirect back to checkout
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// This file doesn't require database connections as it's primarily static content
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Soundex</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            padding-top: 80px;
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title h1 {
            color: #1a1a2e;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .checkout-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .checkout-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h2 {
            color: #1a1a2e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .order-summary {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .summary-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .summary-header h2 {
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .cart-items {
            margin-bottom: 25px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .item-price {
            color: #27ae60;
            font-weight: 600;
        }

        .summary-totals {
            border-top: 2px solid #3498db;
            padding-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .grand-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #e74c3c;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        .payment-methods {
            margin: 25px 0;
        }

        .payment-option {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-option:hover {
            border-color: #3498db;
        }

        .payment-option.selected {
            border-color: #27ae60;
            background-color: #f8fff8;
        }

        .payment-option input {
            margin-right: 10px;
        }

        .place-order-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #27ae60, #219653);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .place-order-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(39, 174, 96, 0.4);
        }

        .empty-cart-message {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }

        .back-to-shopping {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .back-to-shopping:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Header -->
    <nav>
        <ul>
            <div class="logo"><a href="../pages/about.php">
                    <h1>Soun<p>Dex</p>
                    </h1>
                </a></div>
            <li><a href="../pages/home.php">Home</a></li>
            <li><a href="../pages/Gallery.php">Gallery</a></li>
            <li><a href="../pages/faqs.html">FAQs</a></li>
            <li><a href="../pages/services.php">Services</a></li>
            <li><a href="../pages/contact us.php">Contact</a></li>
            <li><a href="../pages/about.php">About</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-title">
            <h1>Secure Checkout</h1>
            <p>Complete your purchase with confidence</p>
        </div>

        <div class="checkout-container" id="checkoutContainer">
            <!-- Empty cart message -->
            <div class="empty-cart-message" id="emptyCartMessage">
                <h2>Your cart is empty</h2>
                <p>Add some products to your cart to checkout</p>
                <a href="Gallery.php" class="back-to-shopping">Continue Shopping</a>
            </div>

            <!-- Checkout form and order summary will be populated here -->
        </div>
    </div>

    <script>
        // Load cart items from localStorage
        function loadCartItems() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const checkoutContainer = document.getElementById('checkoutContainer');
            const emptyCartMessage = document.getElementById('emptyCartMessage');

            if (cart.length === 0) {
                // Show empty cart message
                emptyCartMessage.style.display = 'block';
                return;
            }

            // Hide empty cart message
            emptyCartMessage.style.display = 'none';

            // Create checkout layout
            checkoutContainer.innerHTML = `
                <div class="checkout-form">
                    <div class="form-section">
                        <h2>Shipping Information</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Street Address *</label>
                            <textarea id="address" rows="3" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" required>
                            </div>
                            <div class="form-group">
                                <label for="zipCode">ZIP Code *</label>
                                <input type="text" id="zipCode" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country">Country *</label>
                            <select id="country" required>
                                <option value="">Select Country</option>
                                <option value="India">India</option>
                                <option value="USA">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="Canada">Canada</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2>Payment Method</h2>
                        <div class="payment-methods">
                            <div class="payment-option" onclick="selectPaymentMethod('credit')">
                                <input type="radio" name="payment" value="credit" id="creditCard">
                                <label for="creditCard">Credit/Debit Card</label>
                            </div>
                            <div class="payment-option" onclick="selectPaymentMethod('paypal')">
                                <input type="radio" name="payment" value="paypal" id="paypal">
                                <label for="paypal">PayPal</label>
                            </div>
                            <div class="payment-option" onclick="selectPaymentMethod('cod')">
                                <input type="radio" name="payment" value="cod" id="cashOnDelivery">
                                <label for="cashOnDelivery">Cash on Delivery</label>
                            </div>
                        </div>
                        
                        <div id="cardDetails" style="display: none;">
                            <div class="form-group">
                                <label for="cardNumber">Card Number *</label>
                                <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiryDate">Expiry Date *</label>
                                    <input type="text" id="expiryDate" placeholder="MM/YY">
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV *</label>
                                    <input type="text" id="cvv" placeholder="123">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cardName">Name on Card *</label>
                                <input type="text" id="cardName">
                            </div>
                        </div>
                    </div>
                    
                    <button class="place-order-btn" onclick="placeOrder()">Place Order</button>
                </div>
                
                <div class="order-summary">
                    <div class="summary-header">
                        <h2>Order Summary</h2>
                    </div>
                    <div class="cart-items" id="cartItemsList">
                        ${generateCartItemsHTML(cart)}
                    </div>
                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>₹${calculateSubtotal(cart)}</span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>₹${cart.length > 0 ? '99' : '0'}</span>
                        </div>
                        <div class="total-row grand-total">
                            <span>Total:</span>
                            <span>₹${calculateTotal(cart)}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Generate HTML for cart items
        function generateCartItemsHTML(cart) {
            return cart.map(item => `
                <div class="cart-item">
                    <div class="item-details">
                        <div class="item-name">${item.name}</div>
                        <div class="item-quantity">Qty: ${item.quantity || 1}</div>
                    </div>
                    <div class="item-price">₹${item.price * (item.quantity || 1)}</div>
                </div>
            `).join('');
        }

        // Calculate subtotal
        function calculateSubtotal(cart) {
            return cart.reduce((total, item) => total + (item.price * (item.quantity || 1)), 0);
        }

        // Calculate total with shipping
        function calculateTotal(cart) {
            const subtotal = calculateSubtotal(cart);
            const shipping = cart.length > 0 ? 99 : 0;
            return subtotal + shipping;
        }

        // Select payment method
        function selectPaymentMethod(method) {
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');

            // Show/hide card details based on selection
            const cardDetails = document.getElementById('cardDetails');
            if (method === 'credit') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        }

        // Place order function
        async function placeOrder() {
            // Validate form
            if (!validateForm()) {
                return;
            }

            const placeOrderBtn = document.querySelector('.place-order-btn');
            const originalText = placeOrderBtn.innerText;
            placeOrderBtn.innerText = 'Processing...';
            placeOrderBtn.disabled = true;

            try {
                // Collect data
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const shipping = {
                    firstName: document.getElementById('firstName').value,
                    lastName: document.getElementById('lastName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    address: document.getElementById('address').value,
                    city: document.getElementById('city').value,
                    zipCode: document.getElementById('zipCode').value,
                    country: document.getElementById('country').value
                };
                const paymentMethod = document.querySelector('input[name="payment"]:checked').value;

                // Send to backend
                const response = await fetch('../../backend/php/place_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cart: cart,
                        shipping: shipping,
                        paymentMethod: paymentMethod
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Order placed successfully! Order #' + result.order_number);
                    localStorage.removeItem('cart');
                    window.location.href = 'history.php'; // Redirect to history page
                } else {
                    alert('Failed to place order: ' + result.message);
                    placeOrderBtn.innerText = originalText;
                    placeOrderBtn.disabled = false;
                }

            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while placing the order.');
                placeOrderBtn.innerText = originalText;
                placeOrderBtn.disabled = false;
            }
        }

        // Validate form
        function validateForm() {
            const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'zipCode', 'country'];
            let isValid = true;

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.style.borderColor = '#e74c3c';
                    isValid = false;
                } else {
                    field.style.borderColor = '#ddd';
                }
            });

            // Check if payment method is selected
            const paymentSelected = document.querySelector('input[name="payment"]:checked');
            if (!paymentSelected) {
                alert('Please select a payment method');
                isValid = false;
            }

            if (!isValid) {
                alert('Please fill in all required fields');
            }

            return isValid;
        }

        // Load cart when page loads
        document.addEventListener('DOMContentLoaded', loadCartItems);
    </script>
</body>

</html>