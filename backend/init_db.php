<?php
// Admin-compatible initialization for le_parfum_db
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'le_parfum_db';

// Create database if doesn't exist
$baseConn = new mysqli($host, $username, $password);
if ($baseConn->connect_error) {
    die('Connection failed: ' . $baseConn->connect_error);
}
$baseConn->query("CREATE DATABASE IF NOT EXISTS {$dbname} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$baseConn->select_db($dbname);

// Use $conn for table creation
$conn = $baseConn;

// 1. Create Categories Table
$sql1 = "CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
)";

// 2. Create Products Table (Linking to categories)
$sql2 = "CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_path VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
)";

// 3. Create Users Table
$sql3 = "CREATE TABLE IF NOT EXISTS users (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// 4. Create Cart Table
$sql4 = "CREATE TABLE IF NOT EXISTS cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(customer_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
)";

// 5. Create Orders Table
$sql5 = "CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (customer_id) REFERENCES users(customer_id)
)";

// 6. Create Order Details Table
$sql6 = "CREATE TABLE IF NOT EXISTS order_details (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price_at_purchase DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
)";

// 7. Create Store Admins Table
$sql7 = "CREATE TABLE IF NOT EXISTS admin_users (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$tables = [$sql1, $sql2, $sql3, $sql4, $sql5, $sql6, $sql7];

foreach($tables as $k => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table " . ($k+1) . " created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Optional initial admin manager (do not duplicate on rerun)
$defaultAdmin = 'admin@gmail.com';
$defaultUser = 'admin';
$defaultPass = 'admin123';
$hashedPass = password_hash($defaultPass, PASSWORD_DEFAULT);
$sqlAdminSeed = "INSERT IGNORE INTO store_admins (username, email, password) VALUES ('{$defaultUser}', '{$defaultAdmin}', '{$hashedPass}')";
if ($conn->query($sqlAdminSeed) === TRUE) {
    echo "Default admin record inserted or already exists.<br>";
} else {
    echo "Error inserting default admin: " . $conn->error . "<br>";
}

$conn->close();
?>