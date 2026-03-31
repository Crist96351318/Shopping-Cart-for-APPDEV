<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    handleError('Method not allowed', 405);
}

$category = trim($_GET['category'] ?? '');

if (!$category) {
    handleError('Category parameter is required', 400);
}

// Get category ID
$catStmt = $conn->prepare('SELECT category_id FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1');
$catStmt->bind_param('s', $category);
$catStmt->execute();
$catResult = $catStmt->get_result();

if (!$catResult->num_rows) {
    jsonResponse(['success' => true, 'products' => []]);
    exit;
}

$categoryId = $catResult->fetch_assoc()['category_id'];

// Get products for this category
$products = getProductsByCategory($conn, $categoryId);

jsonResponse(['success' => true, 'products' => $products]);

function getProductsByCategory($conn, $categoryId) {
    $sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_path, c.name AS category_name, pi.image_path AS primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            WHERE p.category_id = ?
            ORDER BY p.product_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $row['product_id'] = intval($row['product_id']);
        $row['price'] = floatval($row['price']);
        $row['stock_quantity'] = intval($row['stock_quantity']);
        $row['image_path'] = $row['primary_image'] ?: $row['image_path'];
        unset($row['primary_image']);
        $products[] = $row;
    }

    return $products;
}
?>