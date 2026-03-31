<?php
// One-time setup script for le_parfum_db
// Called from index.php on first load if DB doesn't exist

$host = 'localhost';
$username = 'root';
$password = '';

// Connect without DB first
$conn = new mysqli($host, $username, $password);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Create DB if not exists
$dbname = 'le_parfum_db';
$conn->query("CREATE DATABASE IF NOT EXISTS {$dbname} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbname);

// Run schema and data setup from canonical SQL file
$sqlFilePath = __DIR__ . '/../Database/le_parfum_db.sql';
if (!file_exists($sqlFilePath)) {
    die('SQL file missing: ' . $sqlFilePath);
}

$sql = file_get_contents($sqlFilePath);
if ($sql === false) {
    die('Failed to read SQL file: ' . $sqlFilePath);
}

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "Database setup completed successfully.";
} else {
    echo "Error setting up database: " . $conn->error;
}

$conn->close();
?>