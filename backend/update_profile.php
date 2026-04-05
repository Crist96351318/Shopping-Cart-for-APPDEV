<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

requireLogin();
$user = getLoggedInUser($conn);
if ($user === null) {
    handleError('Not logged in', 401);
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
$card_number = preg_replace('/\D/', '', $payload['card_number'] ?? '');
$card_last4 = '';
if ($card_number !== '') {
    $card_last4 = substr($card_number, -4);
}
$expiry = trim($payload['expiry'] ?? '');

$profileColumns = [
    "city VARCHAR(100) DEFAULT ''",
    "postal_code VARCHAR(20) DEFAULT ''",
    "card_last4 VARCHAR(4) DEFAULT ''",
    "card_expiry VARCHAR(10) DEFAULT ''"
];
foreach ($profileColumns as $columnDefinition) {
    preg_match('/^([a-z_]+)/', $columnDefinition, $matches);
    $columnName = $matches[1] ?? '';
    if ($columnName && !columnExists($conn, 'users', $columnName)) {
        $conn->query("ALTER TABLE users ADD COLUMN {$columnDefinition}");
    }
}

$stmt = $conn->prepare('UPDATE users SET first_name = ?, last_name = ?, address = ?, city = ?, postal_code = ?, card_last4 = ?, card_expiry = ? WHERE customer_id = ?');
$stmt->bind_param('sssssssi', $first_name, $last_name, $address, $city, $postal_code, $card_last4, $expiry, $user['customer_id']);

if (!$stmt->execute()) {
    handleError('Failed to update profile: ' . $stmt->error, 500);
}

$updatedUser = getLoggedInUser($conn);
jsonResponse(['success' => true, 'message' => 'Profile updated successfully', 'user' => $updatedUser]);
?>