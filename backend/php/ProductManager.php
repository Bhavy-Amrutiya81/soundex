<?php
require_once 'db_config.php';

class ProductManager {
    private $pdo;
    
    public function __construct($database) {
        $this->pdo = $database;
    }
    
    // Get all active products
    public function getAllProducts() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_active = TRUE ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Get product by ID
    public function getProductById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = TRUE");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Add product to cart (supports both logged-in users and guests)
    public function addToCart($userId, $sessionId, $productId, $quantity = 1) {
        try {
            // Check if item already exists in cart
            $stmt = $this->pdo->prepare("SELECT id, quantity FROM cart WHERE (COALESCE(user_id, 0) = COALESCE(?, 0) OR session_id = ?) AND product_id = ?");
            $stmt->execute([$userId, $sessionId, $productId]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existingItem) {
                // Update quantity
                $newQuantity = $existingItem['quantity'] + $quantity;
                $stmt = $this->pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
                return $stmt->execute([$newQuantity, $existingItem['id']]);
            } else {
                // Add new item
                $stmt = $this->pdo->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)");
                return $stmt->execute([$userId, $sessionId, $productId, $quantity]);
            }
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Get cart items (supports both logged-in users and guests)
    public function getCartItems($userId, $sessionId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.*, p.name, p.price, p.image_url 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE (COALESCE(c.user_id, 0) = COALESCE(?, 0) OR c.session_id = ?) AND p.is_active = TRUE
            ");
            $stmt->execute([$userId, $sessionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
    
    // Remove item from cart
    public function removeFromCart($cartId, $userId, $sessionId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cart WHERE id = ? AND (COALESCE(user_id, 0) = COALESCE(?, 0) OR session_id = ?)");
            return $stmt->execute([$cartId, $userId, $sessionId]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Clear cart
    public function clearCart($userId, $sessionId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cart WHERE COALESCE(user_id, 0) = COALESCE(?, 0) OR session_id = ?");
            return $stmt->execute([$userId, $sessionId]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Create order - this requires authentication
    public function createOrder($userId, $cartItems, $shippingAddress, $billingAddress, $paymentMethod) {
        // Ensure the user is logged in by validating userId
        if (!$userId || !is_numeric($userId)) {
            return ['success' => false, 'message' => 'Authentication required to place an order'];
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Calculate total amount
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
            
            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
            
            // Insert order
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (user_id, order_number, total_amount, shipping_address, billing_address, payment_method) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $orderNumber, $totalAmount, $shippingAddress, $billingAddress, $paymentMethod]);
            $orderId = $this->pdo->lastInsertId();
            
            // Insert order items
            foreach ($cartItems as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $stmt = $this->pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price'], $itemTotal]);
            }
            
            // Clear cart
            $this->clearCart($userId, null);
            
            $this->pdo->commit();
            return ['success' => true, 'order_id' => $orderId, 'order_number' => $orderNumber];
            
        } catch(Exception $e) {
            $this->pdo->rollback();
            return ['success' => false, 'message' => 'Order creation failed: ' . $e->getMessage()];
        }
    }
    
    // Merge guest cart with user cart after login
    public function mergeGuestCartToUser($guestSessionId, $userId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE cart SET user_id = ? WHERE session_id = ? AND user_id IS NULL");
            return $stmt->execute([$userId, $guestSessionId]);
        } catch(PDOException $e) {
            return false;
        }
    }
}