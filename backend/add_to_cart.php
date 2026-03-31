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

requireLogin();
$user = getLoggedInUser($conn);

$cart = addToCart($conn, $user['customer_id'], $product_id, $quantity);
$cartDetails = getCart($conn, $user['customer_id']);
jsonResponse(['success' => true, 'message' => 'Product added to cart', 'cart' => $cartDetails]);
?>
