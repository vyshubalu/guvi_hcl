<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

include 'db.php';
header('Content-Type: application/json');

// Get user id from Redis
$token = $_POST['token'] ?? null;
$user_id = null;

if ($redis && $token) {
    $user_id = $redis->get("auth:$token");
}

// Temporary testing fallback
if (!$user_id) {
    $user_id = 1;
}

if (!$user_id) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;

if (!$age || !$dob || !$contact) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

try {
    if (!$mongo_db) {
        throw new Exception("MongoDB not available");
    }
    
    $collection = $mongo_db->user_profile;
    
    $result = $collection->updateOne(
        ['user_id' => (int)$user_id],
        [
            '$set' => [
                'user_id' => (int)$user_id,
                'age' => (int)$age,
                'dob' => $dob,
                'contact' => $contact,
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ]
        ],
        ['upsert' => true]
    );
    
    echo json_encode([
        "status" => "Profile saved",
        "matched" => $result->getMatchedCount(),
        "modified" => $result->getModifiedCount(),
        "upserted" => $result->getUpsertedCount()
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
