<?php
require_once 'config.php';
require_once 'db_connection.php';

function jsonResponse($data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function handleError($message, $statusCode = 400) {
    jsonResponse(['success' => false, 'message' => $message], $statusCode);
}

function getCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

function addToSessionCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product_id = intval($product_id);
    $quantity = max(1, intval($quantity));

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    return $_SESSION['cart'];
}

function updateSessionCart($product_id, $quantity) {
    $product_id = intval($product_id);
    $quantity = intval($quantity);

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    return $_SESSION['cart'];
}

function clearSessionCart() {
    $_SESSION['cart'] = [];
}

function getCartDetails($conn) {
    $cart = getCart();
    if (empty($cart)) {
        return ['items' => [], 'total' => 0.00, 'count' => 0];
    }

    $ids = implode(',', array_map('intval', array_keys($cart)));
    if ($ids === '') {
        return ['items' => [], 'total' => 0.00, 'count' => 0];
    }

    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = $conn->query($sql);

    $items = [];
    $total = 0.0;
    $count = 0;

    while ($row = $result->fetch_assoc()) {
        $quantity = isset($cart[$row['product_id']]) ? intval($cart[$row['product_id']]) : 0;
        if ($quantity <= 0) continue;
        $subtotal = $quantity * floatval($row['price']);
        $items[] = [
            'product_id' => intval($row['product_id']),
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => floatval($row['price']),
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'image_path' => $row['image_path']
        ];
        $total += $subtotal;
        $count += $quantity;
    }

    return ['items' => $items, 'total' => round($total, 2), 'count' => $count];
}

function getLoggedInUser($conn) {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $userId = intval($_SESSION['user_id']);
    $stmt = $conn->prepare('SELECT customer_id, first_name, last_name, email, address, created_at FROM customers WHERE customer_id = ? LIMIT 1');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        handleError('User not logged in', 401);
    }
}

?>
