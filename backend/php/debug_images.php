<?php
require_once 'db_config.php';

try {
    $stmt = $pdo->query("SELECT name, image_url FROM products LIMIT 5");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        echo "Product: " . $product['name'] . "\n";
        echo "Image URL: " . $product['image_url'] . "\n";
        echo "-------------------\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>