<?php
header('Content-Type: application/json');
include 'db.php';

$response = [];

// Test MySQL
try {
    $result = $conn->query("SELECT 1");
    $response['mysql'] = "✅ Connected";
} catch (Exception $e) {
    $response['mysql'] = "❌ Error: " . $e->getMessage();
}

// Test MongoDB
if ($mongo_db) {
    try {
        // Try to list collections
        $collections = $mongo_db->listCollections();
        $response['mongodb'] = "✅ Connected to database: " . $mongo_db->getDatabaseName();
    } catch (Exception $e) {
        $response['mongodb'] = "❌ Error: " . $e->getMessage();
    }
} else {
    $response['mongodb'] = "❌ MongoDB client not initialized";
}

// Test Redis
if ($redis) {
    try {
        $redis->ping();
        $response['redis'] = "✅ Connected";
    } catch (Exception $e) {
        $response['redis'] = "❌ Error: " . $e->getMessage();
    }
} else {
    $response['redis'] = "⚠️ Not available";
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
