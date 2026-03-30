<?php
require_once 'db_connection.php';

function getAllProducts($conn) {
    $sql = "SELECT * FROM products"; // Make sure your table is named 'products'
    $result = $conn->query($sql);
    
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}
?>