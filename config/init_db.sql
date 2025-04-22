-- Drop database if exists
DROP DATABASE IF EXISTS vulncommerce;

-- Create database
CREATE DATABASE vulncommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use database
USE vulncommerce;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default.jpg',
    theme VARCHAR(20) DEFAULT 'default',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT 'default_product.jpg',
    stock INT NOT NULL DEFAULT 0,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Comments table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    rating INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE(user_id, product_id)
);

-- Favorites table
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE(user_id, product_id)
);

-- Compare table
CREATE TABLE compare (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE(user_id, product_id)
);

-- User sessions table
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_data BLOB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample products
INSERT INTO products (name, description, price, stock, category) VALUES
('Smartphone X3', 'High-end smartphone with advanced features', 999.99, 50, 'Electronics'),
('Laptop Pro', 'Professional laptop for heavy workloads', 1499.99, 30, 'Electronics'),
('Wireless Headphones', 'Premium wireless headphones with noise cancellation', 249.99, 100, 'Electronics'),
('Smart Watch', 'Fitness tracker and smartwatch with health monitoring', 199.99, 75, 'Electronics'),
('Casual T-Shirt', 'Comfortable cotton t-shirt for daily wear', 24.99, 200, 'Clothing'),
('Jeans', 'Classic blue jeans with modern fit', 49.99, 150, 'Clothing'),
('Running Shoes', 'Lightweight running shoes for athletes', 89.99, 80, 'Footwear'),
('Coffee Maker', 'Automatic coffee maker for home use', 129.99, 40, 'Home Appliances'),
('Blender', 'High-speed blender for smoothies and more', 79.99, 60, 'Home Appliances'),
('Smart Speaker', 'Voice-controlled smart speaker with assistant', 149.99, 70, 'Electronics');

-- Create admin user (password: admin123)
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', '$2y$10$8MJxKgL8a4SF.4b8hH0h5euvCQrMl7nh8L4tDIaJDtqfzHfuZfuPO'); 