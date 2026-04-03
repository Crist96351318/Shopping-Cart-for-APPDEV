<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

// Check admin access
$user = getLoggedInUser($conn);
if (!$user || !intval($user['is_admin'])) {
    handleError('Admin access required', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    handleError('Method not allowed', 405);
}

// Get all orders with customer information
$sql = "
    SELECT 
        o.order_id,
        o.order_date,
        o.total_amount,
        o.status,
        u.first_name,
        u.last_name,
        u.email,
        u.customer_id
    FROM orders o
    LEFT JOIN users u ON o.customer_id = u.customer_id
    ORDER BY o.order_date DESC
    LIMIT 100
";

$result = $conn->query($sql);
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = [
            'order_id' => intval($row['order_id']),
            'customer_id' => intval($row['customer_id']),
            'customer_name' => trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
            'email' => $row['email'] ?? '',
            'order_date' => $row['order_date'],
            'total_amount' => floatval($row['total_amount']),
            'status' => $row['status'] ?? 'Pending'
        ];
    }
}

jsonResponse(['success' => true, 'orders' => $orders]);
?>
