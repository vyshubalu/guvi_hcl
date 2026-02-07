<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');
include __DIR__ . '/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(["error" => "Missing credentials"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {

        $token = bin2hex(random_bytes(32));

        // ✅ Store token only if Redis is available
        if ($redis) {
            $redis->setex("auth:$token", 3600, $row['id']);
        }

        echo json_encode([
            "token" => $token
        ]);
        exit;
    }
}

echo json_encode([
    "error" => "Invalid email or password"
]);
exit;
?>