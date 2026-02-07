<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');
include 'db.php';

$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (!$name || !$email || !$password) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
$stmt->bind_param("sss", $name, $email, $password_hash);

if($stmt->execute()){
    echo json_encode(["status" => "Registered successfully"]);
}else{
    echo json_encode(["error" => "Registration failed: " . $conn->error]);
}
?>