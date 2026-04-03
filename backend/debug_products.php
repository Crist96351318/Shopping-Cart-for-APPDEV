<?php
require_once 'db_connection.php';
require_once 'functions.php';

header('Content-Type: application/json');

try {
    // Check categories
    $catResult = $conn->query("SELECT * FROM categories");
    $categories = [];
    if ($catResult) {
        while ($row = $catResult->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    // Check products
    $prodResult = $conn->query("SELECT product_id, name, category_id, price, image_path FROM products LIMIT 20");
    $products = [];
    if ($prodResult) {
        while ($row = $prodResult->fetch_assoc()) {
            $products[] = $row;
        }
    }

    // Test specific category
    $testCategory = 'Extrait de Parfum';
    $testResult = $conn->query("SELECT category_id FROM categories WHERE LOWER(name) = LOWER('{$testCategory}')");
    $testCatId = $testResult ? $testResult->fetch_assoc()['category_id'] : null;

    $testProducts = [];
    if ($testCatId) {
        $testProdResult = $conn->query("SELECT p.product_id, p.name, p.price, p.image_path FROM products p WHERE p.category_id = {$testCatId}");
        if ($testProdResult) {
            while ($row = $testProdResult->fetch_assoc()) {
                $testProducts[] = $row;
            }
        }
    }

    jsonResponse([
        'success' => true,
        'database_connection' => true,
        'categories_count' => count($categories),
        'categories' => $categories,
        'products_count' => count($products),
        'products_sample' => $products,
        'test_category' => $testCategory,
        'test_category_id' => $testCatId,
        'test_products_count' => count($testProducts),
        'test_products' => $testProducts
    ]);

} catch (Exception $e) {
    jsonResponse([
        'success' => false,
        'error' => $e->getMessage(),
        'database_connection' => false
    ], 500);
}
?>
