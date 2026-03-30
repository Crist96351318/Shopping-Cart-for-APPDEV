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

$email = trim($payload['email'] ?? '');
$password = $payload['password'] ?? '';

if (!$email || !$password) {
    handleError('email and password are required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    handleError('Invalid email', 400);
}

$stmt = $conn->prepare('SELECT customer_id, first_name, last_name, email, password, address FROM customers WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($password, $user['password'])) {
    handleError('Invalid credentials', 401);
}

$_SESSION['user_id'] = intval($user['customer_id']);
unset($user['password']);
jsonResponse(['success' => true, 'message' => 'Login successful', 'user' => $user]);
?>
