<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

requireLogin();
$user = getLoggedInUser($conn);
$cartDetails = getCartDetails($conn);

if (empty($cartDetails['items'])) {
    handleError('Cart is empty', 400);
}

$conn->begin_transaction();

try {
    $total = $cartDetails['total'];
    $stmt = $conn->prepare('INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, ?)');
    $status = 'Pending';
    $stmt->bind_param('ids', $user['customer_id'], $total, $status);
    if (!$stmt->execute()) {
        throw new Exception('Cannot create order: '.$conn->error);
    }
    $orderId = $conn->insert_id;

    $stmtItem = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)');
    foreach ($cartDetails['items'] as $item) {
        $pid = $item['product_id'];
        $qty = $item['quantity'];
        $price = $item['price'];
        $stmtItem->bind_param('iiid', $orderId, $pid, $qty, $price);
        if (!$stmtItem->execute()) {
            throw new Exception('Cannot insert order item: '.$conn->error);
        }
    }

    $conn->commit();
    clearSessionCart();
    jsonResponse(['success' => true, 'message' => 'Order placed', 'order_id' => $orderId]);
} catch (Exception $e) {
    $conn->rollback();
    handleError($e->getMessage(), 500);
}
?>
