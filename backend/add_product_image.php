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
$imagePath = trim($payload['image_path'] ?? '');

if (!$productId || !$imagePath) {
    handleError('product_id and image_path are required', 400);
}

// Add additional image (not primary)
$stmt = $conn->prepare('INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 0)');
$stmt->bind_param('is', $productId, $imagePath);

if ($stmt->execute()) {
    jsonResponse(['success' => true, 'message' => 'Image added successfully', 'image_id' => $conn->insert_id]);
} else {
    handleError('Failed to add image', 500);
}
?>