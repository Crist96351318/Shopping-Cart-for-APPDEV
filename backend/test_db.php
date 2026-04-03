<?php
require_once 'db_connection.php';
require_once 'functions.php';

header('Content-Type: application/json');

// Test 1: Check DB connection
$connTest = $conn ? 'OK' : 'FAILED';

// Test 2: Count categories
$catCount = 0;
$catResult = $conn->query("SELECT COUNT(*) as cnt FROM categories");
if ($catResult) {
    $row = $catResult->fetch_assoc();
    $catCount = $row['cnt'] ?? 0;
}

// Test 3: List categories
$categories = [];
$catList = $conn->query("SELECT category_id, name FROM categories");
if ($catList) {
    while ($row = $catList->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Test 4: Count products
$prodCount = 0;
$prodResult = $conn->query("SELECT COUNT(*) as cnt FROM products");
if ($prodResult) {
    $row = $prodResult->fetch_assoc();
    $prodCount = $row['cnt'] ?? 0;
}

// Test 5: Test a specific category query
$testCategory = 'Extrait de Parfum';
$testProducts = [];
$testStmt = $conn->prepare("SELECT category_id FROM categories WHERE LOWER(name) = LOWER(?)");
$testStmt->bind_param('s', $testCategory);
$testStmt->execute();
$testCatResult = $testStmt->get_result();
$testCatRow = $testCatResult->fetch_assoc();

if ($testCatRow) {
    $catId = $testCatRow['category_id'];
    $prodStmt = $conn->prepare("
        SELECT p.product_id, p.name, p.price, p.image_path, pi.image_path as primary_image
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        WHERE p.category_id = ?
    ");
    $prodStmt->bind_param('i', $catId);
    $prodStmt->execute();
    $prodTestResult = $prodStmt->get_result();
    
    while ($row = $prodTestResult->fetch_assoc()) {
        $testProducts[] = $row;
    }
}

jsonResponse([
    'success' => true,
    'database_connection' => $connTest,
    'total_categories' => $catCount,
    'categories' => $categories,
    'total_products' => $prodCount,
    'test_category' => $testCategory,
    'test_category_products_count' => count($testProducts),
    'test_products' => array_slice($testProducts, 0, 3)
]);
?>
