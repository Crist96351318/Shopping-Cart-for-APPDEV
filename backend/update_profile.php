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

$first_name = trim($payload['first_name'] ?? '');
$last_name = trim($payload['last_name'] ?? '');
$address = trim($payload['address'] ?? '');
$city = trim($payload['city'] ?? '');
$postal_code = trim($payload['postal_code'] ?? '');
$card_number_input = trim($payload['card_number'] ?? '');
$expiry = trim($payload['expiry'] ?? '');

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

$stmt = $conn->prepare('UPDATE users SET first_name = ?, last_name = ?, address = ?, city = ?, postal_code = ?, card_last4 = ?, card_expiry = ? WHERE customer_id = ?');
if (!$stmt) {
    handleError('Prepare failed: ' . $conn->error, 500);
}

$stmt->bind_param('sssssssi', $first_name, $last_name, $address, $city, $postal_code, $card_last4, $expiry, $customerId);

if (!$stmt->execute()) {
    handleError('Failed to update profile: ' . $stmt->error, 500);
}

$updatedUser = getLoggedInUser($conn);
if (!$updatedUser) {
    handleError('Failed to retrieve updated user data', 500);
}
jsonResponse(['success' => true, 'message' => 'Profile updated successfully', 'user' => $updatedUser]);
?>