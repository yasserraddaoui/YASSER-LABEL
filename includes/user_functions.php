<?php
require_once 'config/database.php';

// Register new user
function registerUser($username, $email, $password) {
    global $conn;
    
    // Check if username or email already exists
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if(mysqli_stmt_num_rows($stmt) > 0) {
        return false; // User already exists
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
    return mysqli_stmt_execute($stmt);
}

// Login user
function loginUser($email, $password) {
    global $conn;
    
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($user = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $user['password'])) {
            return $user;
        }
    }
    
    return false;
}

// Get user by ID
function getUserById($id) {
    global $conn;
    $sql = "SELECT id, username, email, created_at FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Update user profile
function updateUserProfile($id, $username, $email) {
    global $conn;
    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $id);
    return mysqli_stmt_execute($stmt);
}

// Change password
function changePassword($id, $new_password) {
    global $conn;
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $hashed_password, $id);
    return mysqli_stmt_execute($stmt);
}
?> 