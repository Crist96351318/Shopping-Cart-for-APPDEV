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

$productId = intval($payload['product_id'] ?? 0);
if (!$productId) {
    handleError('product_id is required', 400);
}

// Remove image entries
$stmt1 = $conn->prepare('DELETE FROM product_images WHERE product_id = ?');
$stmt1->bind_param('i', $productId);
$stmt1->execute();

$stmt = $conn->prepare('DELETE FROM products WHERE product_id = ?');
$stmt->bind_param('i', $productId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        jsonResponse(['success' => true, 'message' => 'Product deleted successfully']);
    } else {
        handleError('Product not found', 404);
    }
} else {
    handleError('Failed to delete product', 500);
}
?>