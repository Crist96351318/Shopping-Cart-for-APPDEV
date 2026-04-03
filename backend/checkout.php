<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

requireLogin();
$user = getLoggedInUser($conn);
$cartData = getCart($conn, $user['customer_id']);

if (empty($cartData['items'])) {
    handleError('Cart is empty', 400);
}

$conn->begin_transaction();

try {
    $total = $cartData['total'];
    $stmt = $conn->prepare('INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, ?)');
    $status = 'Pending';
    $stmt->bind_param('ids', $user['customer_id'], $total, $status);
    if (!$stmt->execute()) {
        throw new Exception('Cannot create order: '.$conn->error);
    }
    $orderId = $conn->insert_id;

    $stmtItem = $conn->prepare('INSERT INTO order_details (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)');
    $stmtCheckStock = $conn->prepare('SELECT stock_quantity FROM products WHERE product_id = ? FOR UPDATE');
    $stmtUpdateStock = $conn->prepare('UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?');

    foreach ($cartData['items'] as $item) {
        $pid = $item['product_id'];
        $qty = $item['quantity'];
        $price = $item['price'];

        // Check stock
        $stmtCheckStock->bind_param('i', $pid);
        $stmtCheckStock->execute();
        $stockResult = $stmtCheckStock->get_result();
        $stockRow = $stockResult->fetch_assoc();

        if (!$stockRow) {
            throw new Exception('Product not found: ' . $pid);
        }

        if (intval($stockRow['stock_quantity']) < $qty) {
            throw new Exception('Insufficient stock for product ID ' . $pid);
        }

        // Insert order detail
        $stmtItem->bind_param('iiid', $orderId, $pid, $qty, $price);
        if (!$stmtItem->execute()) {
            throw new Exception('Cannot insert order item: ' . $conn->error);
        }

        // Decrement stock
        $stmtUpdateStock->bind_param('ii', $qty, $pid);
        if (!$stmtUpdateStock->execute()) {
            throw new Exception('Cannot update stock for product ID ' . $pid . ': ' . $conn->error);
        }
    }

    $conn->commit();
    clearCart($conn, $user['customer_id']);
    jsonResponse(['success' => true, 'message' => 'Order placed', 'order_id' => $orderId]);
} catch (Exception $e) {
    $conn->rollback();
    handleError($e->getMessage(), 500);
}
?>
