<?php
require_once 'config/functions.php';

try {
    $pdo = dbConnect();
    echo "Database connection successful!";
    
    // Test query
    $stmt = $pdo->query("SELECT * FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($rooms);
    echo "</pre>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}