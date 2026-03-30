<?php
require_once 'db_connection.php';
require_once 'functions.php';

function getAllProducts($conn) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    $products = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['product_id'] = intval($row['product_id']);
            $row['price'] = floatval($row['price']);
            $row['stock_quantity'] = intval($row['stock_quantity']);
            $products[] = $row;
        }
    }

    return $products;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $products = getAllProducts($conn);
    jsonResponse(['success' => true, 'products' => $products]);
} else {
    handleError('Method not allowed', 405);
}
?>