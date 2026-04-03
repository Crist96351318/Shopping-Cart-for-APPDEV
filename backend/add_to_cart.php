<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    handleError('Method not allowed', 405);
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    $payload = $_POST;
}

$product_id = intval($payload['product_id'] ?? 0);
$quantity = intval($payload['quantity'] ?? 1);

if ($product_id <= 0 || $quantity < 1) {
    handleError('product_id and quantity are required (quantity >=1)');
}

// Ensure product exists and has available stock
$stmt = $conn->prepare('SELECT stock_quantity FROM products WHERE product_id = ?');
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    handleError('Product not found', 404);
}

$availableStock = intval($product['stock_quantity']);
if ($availableStock <= 0) {
    handleError('Product is out of stock', 400);
}

// If product already in cart, do not exceed available stock
requireLogin();
$user = getLoggedInUser($conn);
$currentCart = getCart($conn, $user['customer_id']);
$existingItem = array_filter($currentCart['items'], fn($item) => intval($item['product_id']) === $product_id);
$existingQty = 0;
if (!empty($existingItem)) {
    $existingQty = intval(array_values($existingItem)[0]['quantity']);
}

if ($existingQty + $quantity > $availableStock) {
    handleError('Cannot add more than available stock. Available: ' . $availableStock, 400);
}

$cart = addToCart($conn, $user['customer_id'], $product_id, $quantity);
$cartDetails = getCart($conn, $user['customer_id']);
jsonResponse(['success' => true, 'message' => 'Product added to cart', 'cart' => $cartDetails]);
?>
