<?php
require_once 'db_config.php';

try {
    // Replace '../assets' with '../../assets'
    $sql = "UPDATE products SET image_url = REPLACE(image_url, '../assets', '../../assets') WHERE image_url LIKE '../assets%'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo "Updated " . $stmt->rowCount() . " product image paths.\n";

    // Create query to verify
    $stmt = $pdo->query("SELECT name, image_url FROM products LIMIT 3");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Sample Updated Paths:\n";
    foreach ($products as $product) {
        echo $product['image_url'] . "\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>