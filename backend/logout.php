<?php
require_once 'config.php';
require_once 'functions.php';

// Destroy the session to log the user out
$_SESSION = [];
session_destroy();

// Delete the session cookie to ensure complete logout
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
?>
