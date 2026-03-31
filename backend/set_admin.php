<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

// This endpoint is for admin setup only
// Usage: POST /backend/set_admin.php with { email: 'admin@example.com', is_admin: 1 }
// Note: In production, add authentication checks to prevent unauthorized access

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    $payload = $_POST;
}

$email = trim($payload['email'] ?? '');
$is_admin = intval($payload['is_admin'] ?? 0);

if (!$email) {
    handleError('Email is required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    handleError('Invalid email', 400);
}

// Check if user exists
$stmt = $conn->prepare('SELECT customer_id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    handleError('User not found', 404);
}

// Update admin status
$stmt = $conn->prepare('UPDATE users SET is_admin = ? WHERE email = ?');
$stmt->bind_param('is', $is_admin, $email);
$stmt->execute();

jsonResponse(['success' => true, 'message' => 'Admin status updated successfully']);
?>
