<?php
require_once 'db_config.php';

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'order_items'");
    if ($stmt->rowCount() > 0) {
        echo "Table 'order_items' exists.\n";
        $stmt = $pdo->query("DESCRIBE order_items");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo $col['Field'] . " - " . $col['Type'] . "\n";
        }
    } else {
        echo "Table 'order_items' does NOT exist.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>