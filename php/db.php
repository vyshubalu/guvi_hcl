<?php
// Suppress error output to prevent HTML in responses
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// MySQL Connection (for user registration data)
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "user_auth_db";

$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) {
    error_log("MySQL connection failed: " . $conn->connect_error);
    die("MySQL connection failed");
}

// MongoDB Connection (for user profiles: age, dob, contact) - Safe initialization
$mongo_db = null;
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $mongoUri = "mongodb://localhost:27017";
        $mongo_client = new \MongoDB\Client($mongoUri);
        $mongo_db = $mongo_client->user_auth_db;
    } catch (Exception $e) {
        // MongoDB not available, continue without it
        error_log("MongoDB connection error: " . $e->getMessage());
        $mongo_db = null;
    }
}

// Redis (safe init)
$redis = null;
if (extension_loaded('redis')) {
    try {
        $redis = new Redis();
        $redis->connect('localhost', 6379);
    } catch (Exception $e) {
        error_log("Redis connection error: " . $e->getMessage());
        $redis = null;
    }
}
?>
