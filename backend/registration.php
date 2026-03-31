<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    $payload = $_POST;
}

$first_name = trim($payload['first_name'] ?? '');
$last_name = trim($payload['last_name'] ?? '');
$email = trim($payload['email'] ?? '');
$password = $payload['password'] ?? '';
$address = trim($payload['address'] ?? '');

if (!$first_name || !$last_name || !$email || !$password) {
    handleError('first_name, last_name, email and password are required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    handleError('Invalid email address');
}

// Check existing user
$stmt = $conn->prepare('SELECT customer_id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    handleError('Email already registered', 409);
}
$stmt->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password, address) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sssss', $first_name, $last_name, $email, $hashedPassword, $address);

if ($stmt->execute()) {
    $userId = $conn->insert_id;
    $_SESSION['user_id'] = $userId;
    jsonResponse(['success' => true, 'message' => 'Registration successful', 'user' => ['customer_id' => $userId, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'address' => $address]]);
} else {
    $err = $stmt->error ?: $conn->error;
    handleError('Registration failed: ' . $err, 500);
}
$stmt->close();
?>
