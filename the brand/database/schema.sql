-- Create database
CREATE DATABASE IF NOT EXISTS brand_yasser;
USE brand_yasser;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    category VARCHAR(50),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample products
INSERT INTO products (name, description, price, image_url, category, stock) VALUES
('Summer Dress', 'Elegant summer dress perfect for any occasion. Made with high-quality, breathable fabric.', 35.00, '1.jpg', 'Dresses', 50),
('Classic Hoodie', 'Comfortable and warm classic hoodie for everyday wear.', 45.00, 'hoodie1.webp', 'Hoodies', 30),
('Classic Jeans', 'High-quality classic fit jeans for a timeless look.', 59.99, 'jeans.webp', 'Jeans', 40),
('Linen Shirt', 'Lightweight and breathable linen shirt, perfect for warm weather. Features a relaxed fit and natural texture.', 39.99, '1.jpg', 'Shirts', 35),
('Beach Shorts', 'Comfortable and stylish beach shorts with quick-dry fabric. Perfect for summer days and beach activities.', 29.99, '2.webp', 'Shorts', 45); 