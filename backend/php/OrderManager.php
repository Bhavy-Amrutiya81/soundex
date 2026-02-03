<?php
class OrderManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createOrder($userId, $orderData, $items)
    {
        try {
            $this->pdo->beginTransaction();

            $orderNumber = 'ORD-' . strtoupper(uniqid());
            $totalAmount = $this->calculateTotal($items);

            // Format address
            $shippingAddress = json_encode([
                'firstName' => $orderData['firstName'],
                'lastName' => $orderData['lastName'],
                'email' => $orderData['email'],
                'phone' => $orderData['phone'],
                'address' => $orderData['address'],
                'city' => $orderData['city'],
                'zipCode' => $orderData['zipCode'],
                'country' => $orderData['country']
            ]);

            $sql = "INSERT INTO orders (user_id, order_number, total_amount, status, shipping_address, billing_address, payment_method, payment_status, created_at) 
                    VALUES (?, ?, ?, 'pending', ?, ?, ?, 'pending', NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $userId,
                $orderNumber,
                $totalAmount,
                $shippingAddress,
                $shippingAddress, // Assuming billing is same for now
                $orderData['paymentMethod']
            ]);

            $orderId = $this->pdo->lastInsertId();

            // Insert items
            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)";
            $stmtItem = $this->pdo->prepare($sqlItem);

            foreach ($items as $item) {
                // We're trusting frontend price here for simplicity, but in production should lookup product price
                $productId = $this->getProductIdByName($item['name']); // Fallback or lookup
                $unitPrice = $item['price'];
                $quantity = $item['quantity'] ?? 1;
                $totalPrice = $unitPrice * $quantity;

                $stmtItem->execute([$orderId, $productId, $quantity, $unitPrice, $totalPrice]);
            }

            $this->pdo->commit();
            return ['success' => true, 'order_id' => $orderId, 'order_number' => $orderNumber];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getUserOrders($userId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getOrderDetails($orderId, $userId)
    {
        try {
            // Check ownership
            $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
            $stmt->execute([$orderId, $userId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                return null;
            }

            // Get items
            $stmtItems = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmtItems->execute([$orderId]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            // Add items to order array
            $order['items'] = $items;

            return $order;
        } catch (Exception $e) {
            return null;
        }
    }

    private function calculateTotal($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += ($item['price'] * ($item['quantity'] ?? 1));
        }
        // Shipping is hardcoded as 99 in frontend
        if ($total > 0) {
            $total += 99;
        }
        return $total;
    }

    private function getProductIdByName($name)
    {
        // Helper to find product ID since local storage might only have name
        $stmt = $this->pdo->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->execute([$name]);
        $res = $stmt->fetch();
        return $res ? $res['id'] : 0; // 0 for unknown product
    }
}
?>