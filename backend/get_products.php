<?php
require_once 'db_connection.php';
require_once 'functions.php';

function getAllProducts($conn) {
    // Join with order_details to count purchases, sort by most bought
    $sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_path, 
                   c.name AS category_name, pi.image_path AS primary_image,
                   COALESCE(SUM(od.quantity), 0) as total_sold 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.category_id 
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            LEFT JOIN order_details od ON p.product_id = od.product_id
            GROUP BY p.product_id, p.name, p.description, p.price, p.stock_quantity, p.image_path, c.name, pi.image_path
            ORDER BY total_sold DESC, p.product_id ASC";

    $result = $conn->query($sql);
    $products = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['product_id'] = intval($row['product_id']);
            $row['price'] = floatval($row['price']);
            $row['stock_quantity'] = intval($row['stock_quantity']);
            $row['total_sold'] = intval($row['total_sold']);
            
            // Use primary image if available, otherwise use product image_path
            $imgPath = $row['primary_image'] ?: $row['image_path'];
            
            // Ensure the image path is properly formatted
            if ($imgPath) {
                if (!str_starts_with($imgPath, 'assets/')) {
                    $imgPath = 'assets/' . $imgPath;
                }
            } else {
                $imgPath = 'assets/placeholder.png';
            }
            $row['image_path'] = $imgPath;
            unset($row['primary_image']);

            // Get all images for this product
            $imgSql = "SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC";
            $imgStmt = $conn->prepare($imgSql);
            $imgStmt->bind_param('i', $row['product_id']);
            $imgStmt->execute();
            $imgResult = $imgStmt->get_result();
            $images = [];
            while ($imgRow = $imgResult->fetch_assoc()) {
                $imgFile = $imgRow['image_path'];
                if ($imgFile && !str_starts_with($imgFile, 'assets/')) {
                    $imgFile = 'assets/' . $imgFile;
                }
                $images[] = $imgFile;
            }
            $row['images'] = $images;

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