<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

header('Content-Type: application/json');

// Check what user is logged in
$user = getLoggedInUser($conn);

// Get all orders count
$orderCount = 0;
$orderResult = $conn->query("SELECT COUNT(*) as cnt FROM orders");
if ($orderResult) {
    $row = $orderResult->fetch_assoc();
    $orderCount = $row['cnt'] ?? 0;
}

// Get first 3 orders
$orders = [];
$firstOrders = $conn->query("SELECT order_id, customer_id, order_date, total_amount, status FROM orders ORDER BY order_date DESC LIMIT 3");
if ($firstOrders) {
    while ($row = $firstOrders->fetch_assoc()) {
        $orders[] = $row;
    }
}

jsonResponse([
    'success' => true,
    'current_user' => $user,
    'is_admin' => $user ? intval($user['is_admin'] ?? 0) : 0,
    'total_orders_in_db' => $orderCount,
    'first_orders' => $orders,
    'session_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    'session_is_admin' => isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : null
]);
?>
