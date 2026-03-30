<?php
// Since you are using XAMPP, these are usually the defaults
$host = "localhost";
$username = "root";
$password = ""; // XAMPP default is empty
$dbname = "le_parfum_db"; // Make sure to create this in phpMyAdmin!

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// (Expression: Smirk) If you don't see an error, it actually worked!
?>