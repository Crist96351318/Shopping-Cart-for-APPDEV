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

function getCart($conn, $customer_id) {
    $stmt = $conn->prepare('SELECT c.cart_id, c.product_id, c.quantity, p.name, p.description, p.price, p.image_path FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.customer_id = ?');
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $total = 0.0;
    $count = 0;

    while ($row = $result->fetch_assoc()) {
        $quantity = intval($row['quantity']);
        $price = floatval($row['price']);
        $subtotal = $quantity * $price;
        $items[] = [
            'cart_id' => intval($row['cart_id']),
            'product_id' => intval($row['product_id']),
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'image_path' => $row['image_path']
        ];
        $total += $subtotal;
        $count += $quantity;
    }

    return ['items' => $items, 'total' => round($total, 2), 'count' => $count];
}

function addToCart($conn, $customer_id, $product_id, $quantity = 1) {
    $product_id = intval($product_id);
    $quantity = max(1, intval($quantity));

    // Check if item already in cart
    $stmt = $conn->prepare('SELECT cart_id, quantity FROM cart WHERE customer_id = ? AND product_id = ?');
    $stmt->bind_param('ii', $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity
        $row = $result->fetch_assoc();
        $new_quantity = intval($row['quantity']) + $quantity;
        $stmt = $conn->prepare('UPDATE cart SET quantity = ? WHERE cart_id = ?');
        $stmt->bind_param('ii', $new_quantity, $row['cart_id']);
    } else {
        // Insert new
        $stmt = $conn->prepare('INSERT INTO cart (customer_id, product_id, quantity) VALUES (?, ?, ?)');
        $stmt->bind_param('iii', $customer_id, $product_id, $quantity);
    }
    $stmt->execute();

    return getCart($conn, $customer_id);
}

function updateCart($conn, $customer_id, $product_id, $quantity) {
    $product_id = intval($product_id);
    $quantity = intval($quantity);

    if ($quantity <= 0) {
        $stmt = $conn->prepare('DELETE FROM cart WHERE customer_id = ? AND product_id = ?');
        $stmt->bind_param('ii', $customer_id, $product_id);
    } else {
        $stmt = $conn->prepare('UPDATE cart SET quantity = ? WHERE customer_id = ? AND product_id = ?');
        $stmt->bind_param('iii', $quantity, $customer_id, $product_id);
    }
    $stmt->execute();

    return getCart($conn, $customer_id);
}

function clearCart($conn, $customer_id) {
    $stmt = $conn->prepare('DELETE FROM cart WHERE customer_id = ?');
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
}

function getLoggedInUser($conn) {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $userId = intval($_SESSION['user_id']);
    $stmt = $conn->prepare('SELECT customer_id, first_name, last_name, email, address, created_at FROM users WHERE customer_id = ? LIMIT 1');
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
