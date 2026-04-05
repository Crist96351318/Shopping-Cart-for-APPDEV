<?php
header('Content-Type: application/json');

require_once('db_connection.php');
require_once('functions.php');

// Get user_id from query parameter
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

if (!$user_id) {
    echo json_encode([
        'success' => false,
        'message' => 'User ID is required'
    ]);
    exit;
}

try {
    // Fetch notifications for the user
    // Ordered by most recent first
    $query = "SELECT notification_id, title, message, `read`, created_at, order_id 
              FROM notifications 
              WHERE customer_id = ? 
              ORDER BY created_at DESC 
              LIMIT 20";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $notifications = [];
    
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'id' => $row['notification_id'],
            'title' => $row['title'],
            'message' => $row['message'],
            'read' => (bool)$row['read'],
            'created_at' => $row['created_at'],
            'order_id' => $row['order_id']
        ];
    }
    
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'count' => count($notifications)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching notifications: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
