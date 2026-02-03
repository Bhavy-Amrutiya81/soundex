<?php
require_once 'db_config.php';
require_once 'OrderManager.php';

header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

$userId = $_SESSION['user_id'];
$orderData = $input['shipping'];
$orderData['paymentMethod'] = $input['paymentMethod'];
$items = $input['cart'];

$orderManager = new OrderManager($pdo);
$result = $orderManager->createOrder($userId, $orderData, $items);

echo json_encode($result);
?>