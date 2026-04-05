<?php
/**
 * Create a notification for a user
 * 
 * @param mysqli $conn Database connection
 * @param int $customer_id User ID
 * @param string $title Notification title
 * @param string $message Notification message
 * @param int|null $order_id Optional order ID
 * @return bool True if notification was created successfully
 */
function createNotification(&$conn, $customer_id, $title, $message, $order_id = null) {
    $query = "INSERT INTO notifications (customer_id, order_id, title, message, `read`, created_at) 
              VALUES (?, ?, ?, ?, 0, NOW())";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    if ($order_id) {
        $stmt->bind_param("iiss", $customer_id, $order_id, $title, $message);
    } else {
        $stmt->bind_param("isss", $customer_id, $order_id, $title, $message);
    }
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

/**
 * Create an order status update notification
 * 
 * @param mysqli $conn Database connection
 * @param int $order_id Order ID
 * @param string $new_status New status
 * @param string|null $message Optional custom message
 * @return bool True if notification was created
 */
function notifyOrderStatusUpdate(&$conn, $order_id, $new_status, $message = null) {
    // Get customer ID from order
    $query = "SELECT customer_id FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
    
    if (!$order) {
        return false;
    }
    
    $customer_id = $order['customer_id'];
    
    // Create notification based on status
    $title = "Order #" . $order_id . " Status Update";
    if ($message === null) {
        switch ($new_status) {
            case 'Processing':
                $message = "Your order is being processed. We'll ship it soon!";
                break;
            case 'Shipped':
                $message = "Your order has been shipped! Check your email for tracking information.";
                break;
            case 'Delivered':
                $message = "Your order has been delivered! Thank you for shopping with us.";
                break;
            case 'Cancelled':
                $message = "Your order has been cancelled. Please contact support for details.";
                break;
            case 'Pending':
            default:
                $message = "Your order status is: " . $new_status;
                break;
        }
    }
    
    return createNotification($conn, $customer_id, $title, $message, $order_id);
}
?>
