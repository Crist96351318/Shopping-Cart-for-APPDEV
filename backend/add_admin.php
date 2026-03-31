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

$username = trim($payload['username'] ?? '');
$email = trim($payload['email'] ?? '');
$password = $payload['password'] ?? '';

if (!$username || !$email || !$password) {
    handleError('Username, email, and password are required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    handleError('Invalid email format', 400);
}

if (strlen($password) < 6) {
    handleError('Password must be at least 6 characters long', 400);
}

// Check if email already exists in admin_users
$stmt = $conn->prepare('SELECT admin_id FROM admin_users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    handleError('Email already exists', 409);
}

// Check if username already exists
$stmt = $conn->prepare('SELECT admin_id FROM admin_users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    handleError('Username already exists', 409);
}

// Insert new admin
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO admin_users (username, email, password, created_at) VALUES (?, ?, ?, NOW())');
$stmt->bind_param('sss', $username, $email, $hashedPassword);

if ($stmt->execute()) {
    jsonResponse(['success' => true, 'message' => 'Admin registered successfully', 'admin_id' => $conn->insert_id]);
} else {
    handleError('Failed to register admin', 500);
}
?>