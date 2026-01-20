<?php
require_once 'config/database.php';

// Create new order
function createOrder($user_id, $total_amount) {
    global $conn;
    $sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "id", $user_id, $total_amount);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($conn);
}

// Add order item
function addOrderItem($order_id, $product_id, $quantity, $price) {
    global $conn;
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiid", $order_id, $product_id, $quantity, $price);
    return mysqli_stmt_execute($stmt);
}

// Get order by ID
function getOrderById($id) {
    global $conn;
    $sql = "SELECT o.*, u.username, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Get order items
function getOrderItems($order_id) {
    global $conn;
    $sql = "SELECT oi.*, p.name, p.image_url 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get user orders
function getUserOrders($user_id) {
    global $conn;
    $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Update order status
function updateOrderStatus($order_id, $status) {
    global $conn;
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
    return mysqli_stmt_execute($stmt);
}
?> 