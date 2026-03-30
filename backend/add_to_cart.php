<?php
require_once 'config.php';

if (isset($_POST['product_id'])) {
    $p_id = $_POST['product_id'];
    
    // Initialize cart if empty
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add product ID to session
    $_SESSION['cart'][] = $p_id;
    
    // Redirect back to the page you came from
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>