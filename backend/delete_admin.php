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

if (!$adminId) {
    handleError('Admin ID is required', 400);
}

// Check if this is the only admin
$countStmt = $conn->prepare('SELECT COUNT(*) as count FROM admin_users');
$countStmt->execute();
$countResult = $countStmt->get_result();
$count = $countResult->fetch_assoc()['count'];

if ($count <= 1) {
    handleError('Cannot delete the last admin account', 400);
}

// Check if trying to delete current admin (if session exists)
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $adminId && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    handleError('Cannot delete your own admin account', 400);
}

$stmt = $conn->prepare('DELETE FROM admin_users WHERE admin_id = ?');
$stmt->bind_param('i', $adminId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        jsonResponse(['success' => true, 'message' => 'Admin deleted successfully']);
    } else {
        handleError('Admin not found', 404);
    }
} else {
    handleError('Failed to delete admin', 500);
}
?>