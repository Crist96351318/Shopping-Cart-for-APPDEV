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

$adminId = intval($payload['admin_id'] ?? 0);
$newPassword = trim($payload['password'] ?? '');

if (!$adminId || !$newPassword) {
    handleError('admin_id and password are required', 400);
}

if (strlen($newPassword) < 6) {
    handleError('Password must be at least 6 characters long', 400);
}

// Prevent updating own user to avoid lock-out for a moment if the UI is used incorrectly
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $adminId && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    handleError('Use the change-password flow while logged in (not admin table edit)', 400);
}

$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE admin_users SET password = ? WHERE admin_id = ?');
$stmt->bind_param('si', $hash, $adminId);

if ($stmt->execute()) {
    if ($stmt->affected_rows === 0) {
        handleError('Admin not found or password unchanged', 404);
    }
    jsonResponse(['success' => true, 'message' => 'Admin password updated successfully']);
} else {
    handleError('Failed to update admin password', 500);
}
?>