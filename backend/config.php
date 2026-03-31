<?php
// Define base paths for your setup
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Retain specific BASE_URL for your laptop
    define('BASE_URL', 'http://localhost/appdev/Shopping-Cart-for-APPDEV/');
} else {
    // Dynamic BASE_URL for other computers
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . '/');
}

define('INCLUDE_PATH', __DIR__ . '/../');

// Start the session for your cart logic
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>