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

$payload = json_decode(file_get_contents('php://input'), true);
$selectedIds = $payload['selected_product_ids'] ?? [];

if (empty($cartData['items'])) {
    handleError('Cart is empty', 400);
}

// Filter the cart to only include selected items
$itemsToCheckout = [];
if (!empty($selectedIds)) {
    foreach ($cartData['items'] as $item) {
        if (in_array($item['product_id'], $selectedIds)) {
            $itemsToCheckout[] = $item;
        }
    }
    if (empty($itemsToCheckout)) {
        handleError('Selected items are not in the cart', 400);
    }
} else {
    // Fallback if no specific items were sent
    $itemsToCheckout = $cartData['items'];
}

// Calculate the new total for only the selected items
$total = 0;
foreach ($itemsToCheckout as $item) {
    $total += ($item['price'] * $item['quantity']);
}

$conn->begin_transaction();

try {
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

    foreach ($itemsToCheckout as $item) {
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

    // Remove ONLY checked out items from the cart
    $stmtDeleteCart = $conn->prepare('DELETE FROM cart WHERE customer_id = ? AND product_id = ?');
    foreach ($itemsToCheckout as $item) {
        $pid = $item['product_id'];
        $stmtDeleteCart->bind_param('ii', $user['customer_id'], $pid);
        $stmtDeleteCart->execute();
    }

    $conn->commit();
    
    // Optional: Send Notification (if file exists)
    if (file_exists('notification_helper.php')) {
        require_once('notification_helper.php');
        createNotification($conn, $user['customer_id'], "Order Received", "Thank you for your order! Order #" . $orderId, $orderId);
    }

    jsonResponse(['success' => true, 'message' => 'Order placed', 'order_id' => $orderId]);
} catch (Exception $e) {
    $conn->rollback();
    handleError($e->getMessage(), 500);
}
?>