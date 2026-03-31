<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

// This is a one-time setup script to create the initial admin account
// Delete this file after running it for security

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    $payload = $_POST;
}

$email = trim($payload['email'] ?? '');
$password = $payload['password'] ?? '';
$first_name = $payload['first_name'] ?? 'Admin';
$last_name = $payload['last_name'] ?? 'User';

if (!$email || !$password) {
    handleError('Email and password are required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Allow simple email format for admin
    if (strpos($email, '@') === false) {
        $email = $email; // Allow "admin" as email for setup
    } else {
        handleError('Invalid email format', 400);
    }
}

// Check if user already exists
$stmt = $conn->prepare('SELECT customer_id FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    handleError('User with this email already exists', 400);
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new admin user
$stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password, is_admin) VALUES (?, ?, ?, ?, 1)');
$stmt->bind_param('ssss', $first_name, $last_name, $email, $hashed_password);

if ($stmt->execute()) {
    $user_id = $conn->insert_id();
    jsonResponse([
        'success' => true,
        'message' => 'Admin account created successfully',
        'admin' => [
            'customer_id' => $user_id,
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'is_admin' => 1
        ]
    ]);
} else {
    handleError('Failed to create admin account: ' . $conn->error, 500);
}
?>
