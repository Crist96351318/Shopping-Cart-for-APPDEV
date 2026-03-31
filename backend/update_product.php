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
$name = trim($payload['name'] ?? '');
$categoryName = trim($payload['category'] ?? '');
$description = trim($payload['description'] ?? '');
$price = floatval($payload['price'] ?? 0);
$stock = intval($payload['stock_quantity'] ?? 0);
$imagePath = trim($payload['image_path'] ?? '');

if (!$productId || !$name || !$categoryName || $price <= 0 || $stock < 0) {
    handleError('Missing/invalid product data', 400);
}

// Ensure categories exist
$catStmt = $conn->prepare('SELECT category_id FROM categories WHERE name = ? LIMIT 1');
$catStmt->bind_param('s', $categoryName);
$catStmt->execute();
$catResult = $catStmt->get_result();
if ($catRow = $catResult->fetch_assoc()) {
    $categoryId = intval($catRow['category_id']);
} else {
    $insCat = $conn->prepare('INSERT INTO categories (name) VALUES (?)');
    $insCat->bind_param('s', $categoryName);
    $insCat->execute();
    $categoryId = intval($conn->insert_id);
}

// Update product
$prodStmt = $conn->prepare('UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, stock_quantity = ?, image_path = ? WHERE product_id = ?');
$prodStmt->bind_param('issdisi', $categoryId, $name, $description, $price, $stock, $imagePath, $productId);

if (!$prodStmt->execute()) {
    handleError('Failed to update product: ' . $conn->error, 500);
}

// Update primary image in product_images if provided
if ($imagePath) {
    // First, set all images for this product to not primary
    $conn->query("UPDATE product_images SET is_primary = 0 WHERE product_id = $productId");
    // Then insert or update the primary image
    $imgStmt = $conn->prepare('INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE image_path = VALUES(image_path), is_primary = 1');
    $imgStmt->bind_param('is', $productId, $imagePath);
    $imgStmt->execute();
}

jsonResponse(['success' => true, 'message' => 'Product updated successfully']);
?>