-- Bootstrap SQL for le_parfum_db
-- Run once per machine: mysql -u root -p < le_parfum_db_bootstrap.sql

CREATE DATABASE IF NOT EXISTS le_parfum_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE le_parfum_db;

CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_path VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE IF NOT EXISTS product_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE IF NOT EXISTS users (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(customer_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (customer_id) REFERENCES users(customer_id)
);

CREATE TABLE IF NOT EXISTS order_details (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price_at_purchase DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE IF NOT EXISTS admin_users (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

    -- Default store admin
    INSERT INTO admin_users (username, email, password)
    VALUES ('admin', 'admin@gmail.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36DXs7FG')
    ON DUPLICATE KEY UPDATE username = username;

-- Seed data for fragrance categories and products
-- Run this after creating the database schema

USE le_parfum_db;

-- Insert fragrance categories
INSERT INTO categories (name) VALUES
('Extrait de Parfum'),
('Eau de Parfum'),
('Eau de Toilette'),
('Eau de Cologne'),
('Eau Fraiche')
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample products for each category
INSERT INTO products (category_id, name, description, price, stock_quantity, image_path) VALUES
-- Extrait de Parfum
((SELECT category_id FROM categories WHERE name = 'Extrait de Parfum'), 'Island Khadlaj', 'A luxurious oriental fragrance with notes of saffron, rose, and amber', 120.00, 25, '../assets/cat1.png'),

-- Eau de Parfum
((SELECT category_id FROM categories WHERE name = 'Eau de Parfum'), 'Chanel N°5', 'The iconic floral aldehyde fragrance that revolutionized perfumery', 150.00, 30, '../assets/cat2.png'),

-- Eau de Toilette
((SELECT category_id FROM categories WHERE name = 'Eau de Toilette'), 'Gucci Bloom', 'A fresh and feminine floral fragrance with notes of jasmine and tuberose', 95.00, 40, '../assets/cat3.png'),

-- Eau de Cologne
((SELECT category_id FROM categories WHERE name = 'Eau de Cologne'), 'Jo Malone London', 'A sophisticated citrus cologne with neroli and bergamot notes', 80.00, 35, '../assets/cat4.png'),

-- Eau Fraiche
((SELECT category_id FROM categories WHERE name = 'Eau Fraiche'), 'Chanel Eau Fraîche', 'A light and refreshing citrus fragrance perfect for everyday wear', 75.00, 45, '../assets/cat5.png'),

-- Additional sample products for variety
((SELECT category_id FROM categories WHERE name = 'Eau de Parfum'), 'Dior Sauvage', 'A fresh and spicy masculine fragrance with pepper and lavender', 110.00, 28, '../assets/sauvage.png'),
((SELECT category_id FROM categories WHERE name = 'Eau de Parfum'), 'Yves Saint Laurent Black Opium', 'An addictive oriental vanilla fragrance with coffee and white flowers', 125.00, 22, '../assets/black-opium.png'),
((SELECT category_id FROM categories WHERE name = 'Eau de Toilette'), 'Acqua di Parma Colonia', 'A classic Italian citrus cologne with Sicilian lemon and orange', 85.00, 38, '../assets/colonia.png'),
((SELECT category_id FROM categories WHERE name = 'Eau de Toilette'), 'Tom Ford Oud Wood', 'A luxurious woody fragrance with rare oud and sandalwood', 180.00, 15, '../assets/oud-wood.png'),
((SELECT category_id FROM categories WHERE name = 'Eau de Cologne'), 'Penhaligons Lily of the Valley', 'A delicate floral cologne with lily of the valley and rose', 95.00, 20, '../assets/lily-valley.png')
ON DUPLICATE KEY UPDATE name = name;

-- Set primary images for products
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
((SELECT product_id FROM products WHERE name = 'Island Khadlaj' LIMIT 1), '../assets/cat1.png', 1),
((SELECT product_id FROM products WHERE name = 'Chanel N°5' LIMIT 1), '../assets/cat2.png', 1),
((SELECT product_id FROM products WHERE name = 'Gucci Bloom' LIMIT 1), '../assets/cat3.png', 1),
((SELECT product_id FROM products WHERE name = 'Jo Malone London' LIMIT 1), '../assets/cat4.png', 1),
((SELECT product_id FROM products WHERE name = 'Chanel Eau Fraîche' LIMIT 1), '../assets/cat5.png', 1),
((SELECT product_id FROM products WHERE name = 'Dior Sauvage' LIMIT 1), '../assets/sauvage.png', 1),
((SELECT product_id FROM products WHERE name = 'Yves Saint Laurent Black Opium' LIMIT 1), '../assets/black-opium.png', 1),
((SELECT product_id FROM products WHERE name = 'Acqua di Parma Colonia' LIMIT 1), '../assets/colonia.png', 1),
((SELECT product_id FROM products WHERE name = 'Tom Ford Oud Wood' LIMIT 1), '../assets/oud-wood.png', 1),
((SELECT product_id FROM products WHERE name = 'Penhaligons Lily of the Valley' LIMIT 1), '../assets/lily-valley.png', 1)
ON DUPLICATE KEY UPDATE is_primary = 1;