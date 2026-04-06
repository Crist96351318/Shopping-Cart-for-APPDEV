<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';
require_once 'notification_helper.php';

// Check admin access
$user = getLoggedInUser($conn);
if (!$user || !intval($user['is_admin'])) {
    handleError('Admin access required', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    handleError('Invalid request', 400);
}

$order_id = intval($payload['order_id'] ?? 0);
$status = trim($payload['status'] ?? '');

if (!$order_id || !$status) {
    handleError('order_id and status are required', 400);
}

// Validate status
$validStatuses = ['Pending', 'Shipped', 'Completed', 'Cancelled'];
if (!in_array($status, $validStatuses)) {
    handleError('Invalid status value', 400);
}

// Update order status
$stmt = $conn->prepare('UPDATE orders SET status = ? WHERE order_id = ?');
$stmt->bind_param('si', $status, $order_id);

if ($stmt->execute()) {
    // Send notification to customer about status update
    notifyOrderStatusUpdate($conn, $order_id, $status);
    
    jsonResponse([
        'success' => true,
        'message' => 'Order status updated successfully',
        'order_id' => $order_id,
        'new_status' => $status
    ]);
} else {
    handleError('Failed to update order status', 500);
}
?>
