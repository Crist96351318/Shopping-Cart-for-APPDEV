<?php
header('Content-Type: application/json');

require_once('db_connection.php');
require_once('functions.php');

// Get input
$input = json_decode(file_get_contents('php://input'), true);
$notification_id = isset($input['notification_id']) ? intval($input['notification_id']) : null;

if (!$notification_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Notification ID is required'
    ]);
    exit;
}

try {
    // Mark notification as read
    $query = "UPDATE notifications SET `read` = 1 WHERE notification_id = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $notification_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'message' => 'Notification marked as read'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error marking notification as read: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
