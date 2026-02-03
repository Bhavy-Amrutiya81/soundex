<?php
require_once 'db_config.php';
require_once 'OrderManager.php';

// Mock user ID (assuming admin user created earlier exists, id might be different but let's try to get it)
$stmt = $pdo->query("SELECT id FROM users WHERE username = 'admin' LIMIT 1");
$user = $stmt->fetch();

if (!$user) {
    echo "Admin user not found, cannot test.\n";
    exit;
}

$userId = $user['id'];
$orderManager = new OrderManager($pdo);

// Mock Data
$orderData = [
    'firstName' => 'Test',
    'lastName' => 'User',
    'email' => 'test@example.com',
    'phone' => '1234567890',
    'address' => '123 Test St',
    'city' => 'Test City',
    'zipCode' => '12345',
    'country' => 'India',
    'paymentMethod' => 'cod'
];

$items = [
    [
        'name' => 'Sony SRS-XB33',
        'price' => 15999,
        'quantity' => 1
    ]
];

echo "Creating test order for User ID: $userId...\n";
$result = $orderManager->createOrder($userId, $orderData, $items);

if ($result['success']) {
    echo "Order created successfully! Order #: " . $result['order_number'] . "\n";

    // Verify by fetching
    $orders = $orderManager->getUserOrders($userId);
    echo "User now has " . count($orders) . " orders.\n";
    print_r($orders[0]);
} else {
    echo "Failed to create order: " . $result['message'] . "\n";
}
?>