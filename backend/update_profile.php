<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

requireLogin();
$user = getLoggedInUser($conn);
if ($user === null || !isset($user['customer_id'])) {
    handleError('Not logged in or invalid user', 401);
}

$customerId = intval($user['customer_id']);
if ($customerId <= 0) {
    handleError('Invalid user ID', 401);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    $payload = $_POST;
}

$first_name = isset($payload['first_name']) ? trim($payload['first_name']) : null;
$last_name = isset($payload['last_name']) ? trim($payload['last_name']) : null;
$address = isset($payload['address']) ? trim($payload['address']) : null;
$city = isset($payload['city']) ? trim($payload['city']) : null;
$postal_code = isset($payload['postal_code']) ? trim($payload['postal_code']) : null;
$card_number_input = isset($payload['card_number']) ? trim($payload['card_number']) : null;
$expiry = isset($payload['expiry']) ? trim($payload['expiry']) : null;
$old_password = isset($payload['old_password']) ? trim($payload['old_password']) : '';
$new_password = isset($payload['new_password']) ? trim($payload['new_password']) : '';
$confirm_password = isset($payload['confirm_password']) ? trim($payload['confirm_password']) : '';

// Load current user row so missing fields can be preserved
$stmt = $conn->prepare('SELECT first_name, last_name, address, city, postal_code, card_last4, card_expiry, password FROM users WHERE customer_id = ?');
$stmt->bind_param('i', $customerId);
$stmt->execute();
$currentResult = $stmt->get_result();
$currentUser = $currentResult->fetch_assoc();
$stmt->close();

if (!$currentUser) {
    handleError('User not found', 404);
}

$first_name = $first_name !== null ? $first_name : $currentUser['first_name'];
$last_name = $last_name !== null ? $last_name : $currentUser['last_name'];
$address = $address !== null ? $address : $currentUser['address'];
$city = $city !== null ? $city : $currentUser['city'];
$postal_code = $postal_code !== null ? $postal_code : $currentUser['postal_code'];
$expiry = $expiry !== null ? $expiry : $currentUser['card_expiry'];
$card_last4 = $currentUser['card_last4'];

// Handle password change if provided
$password_hash = null;
if (!empty($new_password)) {
    if (empty($old_password)) {
        handleError('Current password is required to change password', 400);
    }
    if ($new_password !== $confirm_password) {
        handleError('New passwords do not match', 400);
    }
    if (strlen($new_password) < 6) {
        handleError('Password must be at least 6 characters long', 400);
    }
    
    // Verify old password
    $stmt = $conn->prepare('SELECT password FROM users WHERE customer_id = ?');
    $stmt->bind_param('i', $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userRecord = $result->fetch_assoc();
    
    if (!$userRecord || !password_verify($old_password, $userRecord['password'])) {
        handleError('Current password is incorrect', 401);
    }
    
    // Hash new password
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
}

// Only update card if a new card number (non-masked) is provided
$card_last4 = $user['card_last4'] ?? '';
if ($card_number_input && !strpos($card_number_input, '*')) {
    $card_number = preg_replace('/\D/', '', $card_number_input);
    if ($card_number && strlen($card_number) >= 4) {
        $card_last4 = substr($card_number, -4);
    }
}

// Ensure columns exist in database
$requiredColumns = [
    'city' => "city VARCHAR(100) DEFAULT ''",
    'postal_code' => "postal_code VARCHAR(20) DEFAULT ''",
    'card_last4' => "card_last4 VARCHAR(4) DEFAULT ''",
    'card_expiry' => "card_expiry VARCHAR(10) DEFAULT ''"
];

foreach ($requiredColumns as $columnName => $columnDef) {
    $checkQuery = "SHOW COLUMNS FROM users LIKE '{$columnName}'";
    $checkResult = $conn->query($checkQuery);
    
    if (!$checkResult || $checkResult->num_rows === 0) {
        $alterSQL = "ALTER TABLE users ADD COLUMN {$columnDef}";
        if (!$conn->query($alterSQL)) {
            handleError('Database error adding column ' . $columnName . ': ' . $conn->error, 500);
        }
    }
}

$stmt = $conn->prepare('UPDATE users SET first_name = ?, last_name = ?, address = ?, city = ?, postal_code = ?, card_last4 = ?, card_expiry = ?' . ($password_hash ? ', password = ?' : '') . ' WHERE customer_id = ?');
if (!$stmt) {
    handleError('Prepare failed: ' . $conn->error, 500);
}

if ($password_hash) {
    $stmt->bind_param('sssssssssi', $first_name, $last_name, $address, $city, $postal_code, $card_last4, $expiry, $password_hash, $customerId);
} else {
    $stmt->bind_param('sssssssi', $first_name, $last_name, $address, $city, $postal_code, $card_last4, $expiry, $customerId);
}

if (!$stmt->execute()) {
    handleError('Failed to update profile: ' . $stmt->error, 500);
}

$updatedUser = getLoggedInUser($conn);
if (!$updatedUser) {
    handleError('Failed to retrieve updated user data', 500);
}
jsonResponse(['success' => true, 'message' => 'Profile updated successfully', 'user' => $updatedUser]);
?>