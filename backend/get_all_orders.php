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
        $orderId = intval($row['order_id']);

        // Retrieve itemized details
        $detailsStmt = $conn->prepare("SELECT od.product_id, od.quantity, od.price_at_purchase, p.name AS product_name FROM order_details od LEFT JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ?");
        $detailsStmt->bind_param('i', $orderId);
        $detailsStmt->execute();
        $detailsResult = $detailsStmt->get_result();
        $items = [];
        while ($detail = $detailsResult->fetch_assoc()) {
            $items[] = [
                'product_id' => intval($detail['product_id']),
                'product_name' => $detail['product_name'] ?? 'Unknown',
                'quantity' => intval($detail['quantity']),
                'price_at_purchase' => floatval($detail['price_at_purchase'])
            ];
        }

        $orders[] = [
            'order_id' => $orderId,
            'customer_id' => intval($row['customer_id']),
            'customer_name' => trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
            'order_date' => $row['order_date'],
            'total_amount' => floatval($row['total_amount']),
            'status' => $row['status'] ?? 'Pending',
            'items' => $items
        ];
    }
}

jsonResponse(['success' => true, 'orders' => $orders]);
?>
