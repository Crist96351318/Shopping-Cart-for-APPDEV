<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) {
    $payload = $_REQUEST;
}

switch ($method) {
    case 'GET':
        $cartDetails = getCartDetails($conn);
        jsonResponse(['success' => true, 'cart' => $cartDetails]);
        break;

    case 'POST':
        $product_id = intval($payload['product_id'] ?? 0);
        $quantity = intval($payload['quantity'] ?? 1);
        if ($product_id <= 0 || $quantity < 1) {
            handleError('product_id and quantity are required', 400);
        }
        addToSessionCart($product_id, $quantity);
        jsonResponse(['success' => true, 'message' => 'Item added', 'cart' => getCartDetails($conn)]);
        break;

    case 'PUT':
    case 'PATCH':
        $product_id = intval($payload['product_id'] ?? 0);
        $quantity = intval($payload['quantity'] ?? 0);
        if ($product_id <= 0) {
            handleError('product_id is required', 400);
        }
        updateSessionCart($product_id, $quantity);
        jsonResponse(['success' => true, 'message' => 'Cart updated', 'cart' => getCartDetails($conn)]);
        break;

    case 'DELETE':
        $product_id = intval($payload['product_id'] ?? 0);
        if ($product_id <= 0) {
            handleError('product_id is required', 400);
        }
        updateSessionCart($product_id, 0);
        jsonResponse(['success' => true, 'message' => 'Item removed', 'cart' => getCartDetails($conn)]);
        break;

    default:
        handleError('Method not allowed', 405);
}
?>
