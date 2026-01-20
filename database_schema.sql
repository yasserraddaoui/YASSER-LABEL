-- Create the database
CREATE DATABASE IF NOT EXISTS brand_yasser_db;
USE brand_yasser_db;

-- Users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    stock_quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product sizes table
CREATE TABLE product_sizes (
    size_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    size_name VARCHAR(10) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Order items table
CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    size_id INT,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE SET NULL,
    FOREIGN KEY (size_id) REFERENCES product_sizes(size_id) ON DELETE SET NULL
);

-- Cart table
CREATE TABLE cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Cart items table
CREATE TABLE cart_items (
    cart_item_id INT PRIMARY KEY AUTO_INCREMENT,
    cart_id INT,
    product_id INT,
    size_id INT,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES cart(cart_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (size_id) REFERENCES product_sizes(size_id) ON DELETE CASCADE
);

-- Insert sample products
INSERT INTO products (name, description, price, image_url, stock_quantity) VALUES
('Summer Dress', 'Light and comfortable summer dress', 35.00, '1.jpg', 50),
('Linen Shirt', 'Classic linen shirt for any occasion', 45.00, '2.webp', 30),
('Beach Shorts', 'Perfect for beach days', 25.00, '3.webp', 40),
('Classic Hoodie', 'Warm and stylish hoodie', 65.00, 'hoodie1.webp', 25);

-- Insert sample sizes for each product
INSERT INTO product_sizes (product_id, size_name, stock_quantity) VALUES
(1, 'S', 10),
(1, 'M', 15),
(1, 'L', 15),
(1, 'XL', 10),
(2, 'S', 8),
(2, 'M', 10),
(2, 'L', 12),
(3, 'S', 10),
(3, 'M', 15),
(3, 'L', 15),
(4, 'S', 5),
(4, 'M', 10),
(4, 'L', 10); 