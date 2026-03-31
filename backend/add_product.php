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

$name = trim($payload['name'] ?? '');
$categoryName = trim($payload['category'] ?? '');
$description = trim($payload['description'] ?? '');
$price = floatval($payload['price'] ?? 0);
$stock = intval($payload['stock_quantity'] ?? 0);
$imagePath = trim($payload['image_path'] ?? '');

if (!$name || !$categoryName || $price <= 0 || $stock < 0) {
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

// Insert product
$prodStmt = $conn->prepare('INSERT INTO products (category_id, name, description, price, stock_quantity, image_path) VALUES (?, ?, ?, ?, ?, ?)');
$prodStmt->bind_param('issdis', $categoryId, $name, $description, $price, $stock, $imagePath);

if (!$prodStmt->execute()) {
    handleError('Failed to insert product: ' . $conn->error, 500);
}

$productId = intval($conn->insert_id);

// Maintain product_images table if exists
$imgStmt = $conn->prepare('INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 1)');
if ($imgStmt) {
    $imgStmt->bind_param('is', $productId, $imagePath);
    $imgStmt->execute();
}

jsonResponse(['success' => true, 'message' => 'Product added successfully', 'product_id' => $productId]);
?>