<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    $payload = $_REQUEST;
}

requireLogin();

$customer_id = intval($_SESSION['user_id']);

switch ($method) {
    case 'GET':
        $cartDetails = getCart($conn, $customer_id);
        jsonResponse(['success' => true, 'cart' => $cartDetails]);
        break;

    case 'POST':
        $product_id = intval($payload['product_id'] ?? 0);
        $quantity = intval($payload['quantity'] ?? 1);
        if ($product_id <= 0 || $quantity < 1) {
            handleError('product_id and quantity are required', 400);
        }
        $cartDetails = addToCart($conn, $customer_id, $product_id, $quantity);
        jsonResponse(['success' => true, 'message' => 'Item added', 'cart' => $cartDetails]);
        break;

    case 'PUT':
    case 'PATCH':
        $product_id = intval($payload['product_id'] ?? 0);
        $quantity = intval($payload['quantity'] ?? 0);
        if ($product_id <= 0) {
            handleError('product_id is required', 400);
        }
        $cartDetails = updateCart($conn, $customer_id, $product_id, $quantity);
        jsonResponse(['success' => true, 'message' => 'Cart updated', 'cart' => $cartDetails]);
        break;

    case 'DELETE':
        $product_id = intval($payload['product_id'] ?? 0);
        if ($product_id <= 0) {
            handleError('product_id is required', 400);
        }
        $cartDetails = updateCart($conn, $customer_id, $product_id, 0);
        jsonResponse(['success' => true, 'message' => 'Item removed', 'cart' => $cartDetails]);
        break;

    default:
        handleError('Method not allowed', 405);
}
?>
