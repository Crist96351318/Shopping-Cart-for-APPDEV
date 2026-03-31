<?php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    handleError('Method not allowed', 405);
}

$stmt = $conn->prepare('SELECT admin_id, username, email, created_at FROM admin_users ORDER BY created_at DESC');
$stmt->execute();
$result = $stmt->get_result();

$admins = [];
while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}

jsonResponse(['success' => true, 'admins' => $admins]);
?>