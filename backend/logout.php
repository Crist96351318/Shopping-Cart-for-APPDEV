<?php
require_once 'config.php';
require_once 'functions.php';

// Destroy the session to log the user out
$_SESSION = [];
session_destroy();

jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
?>