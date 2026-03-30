<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    handleError('Method not allowed', 405);
}

requireLogin();
$user = getLoggedInUser($conn);

$stmt = $conn->prepare('SELECT order_id, order_date, total_amount, status FROM orders WHERE customer_id = ? ORDER BY order_date DESC');
$stmt->bind_param('i', $user['customer_id']);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = [
        'order_id' => intval($row['order_id']),
        'order_date' => $row['order_date'],
        'total_amount' => floatval($row['total_amount']),
        'status' => $row['status']
    ];
}

jsonResponse(['success' => true, 'orders' => $orders]);
?>