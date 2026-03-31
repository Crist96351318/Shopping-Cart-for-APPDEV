<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    handleError('Method not allowed', 405);
}

requireLogin();
$user = getLoggedInUser($conn);
$order_id = intval($_GET['order_id'] ?? 0);

if ($order_id <= 0) handleError('order_id is required', 400);

// Fetch order
$stmt = $conn->prepare('SELECT order_id, order_date, total_amount, status FROM orders WHERE order_id = ? AND customer_id = ? LIMIT 1');
$stmt->bind_param('ii', $order_id, $user['customer_id']);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    handleError('Order not found or access denied', 404);
}

// Fetch items
$stmtItems = $conn->prepare('SELECT od.quantity, od.price_at_purchase, p.name FROM order_details od JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ?');
$stmtItems->bind_param('i', $order_id);
$stmtItems->execute();
$itemsResult = $stmtItems->get_result();

$items = [];
while ($row = $itemsResult->fetch_assoc()) {
    $items[] = $row;
}

$order['items'] = $items;
jsonResponse(['success' => true, 'order' => $order]);
?>