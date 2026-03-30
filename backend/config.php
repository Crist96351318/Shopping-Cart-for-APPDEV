<?php
// Define base paths for your ACER laptop setup
define('BASE_URL', 'http://localhost/appdev/Shopping-Cart-for-APPDEV/');
define('INCLUDE_PATH', __DIR__ . '/../');

// Start the session for your cart logic
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>