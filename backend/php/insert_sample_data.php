<?php
require_once 'db_config.php';

// Insert sample products
$products = [
    ['Sony SRS-XB33', 'Powerful portable Bluetooth speaker with extra bass', 15999, 'Speakers', '../../assets/images/product_gallery/32.jpg', 50],
    ['Portronics SoundDrum', 'Premium wireless speaker with 360° sound', 1500, 'Speakers', '../../assets/images/product_gallery/33.jpg', 30],
    ['boAt Stone 352', 'Compact Bluetooth speaker with HD sound', 1200, 'Speakers', '../../assets/images/product_gallery/34.jpg', 25],
    ['pTron Fusion Go', 'Waterproof outdoor speaker', 999, 'Speakers', '../../assets/images/product_gallery/35.jpg', 40],
    ['boAt Stone 1200', 'High power party speaker', 3999, 'Speakers', '../../assets/images/product_gallery/36.jpg', 20],
    ['UE Wonderboom 4', 'Ultimate Ears waterproof speaker', 6499, 'Speakers', '../../assets/images/product_gallery/37.jpg', 15],
    ['UE MEGABOOM 3', 'Premium 360° wireless speaker', 15999, 'Speakers', '../../assets/images/product_gallery/38.jpg', 10],
    ['Soundcore Motion X500', 'Anker flagship speaker with premium sound', 10999, 'Speakers', '../../assets/images/product_gallery/39.jpg', 12]
];

try {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image_url, stock_quantity) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($products as $product) {
        $stmt->execute($product);
    }
    
    echo "Sample products inserted successfully!\n";
    
    // Insert sample services
    $services = [
        ['Basic Repair', 'Standard device repair service', 500, 60],
        ['Advanced Repair', 'Complex repair with parts replacement', 1500, 120],
        ['Diagnostic Check', 'Complete device health check', 200, 30],
        ['Software Update', 'Operating system and software updates', 300, 45],
        ['Cleaning Service', 'Professional device cleaning', 150, 20]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO services (name, description, base_price, duration_minutes) VALUES (?, ?, ?, ?)");
    
    foreach ($services as $service) {
        $stmt->execute($service);
    }
    
    echo "Sample services inserted successfully!\n";
    
    // Insert admin user
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@soundex.com', $adminPassword, 'Admin', 'User', 'admin', true]);
    
    echo "Admin user created successfully! (Username: admin, Password: admin123)\n";
    
} catch(PDOException $e) {
    echo "Error inserting sample data: " . $e->getMessage() . "\n";
}