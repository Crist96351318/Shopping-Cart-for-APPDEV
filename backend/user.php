<?php
session_start();
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    handleError('Method not allowed', 405);
}

$user = getLoggedInUser($conn);
if ($user === null) {
    handleError('Not logged in', 401);
}
jsonResponse(['success' => true, 'user' => $user]);
?>
