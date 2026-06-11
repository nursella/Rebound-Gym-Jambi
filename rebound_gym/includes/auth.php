<?php
/**
 * Authentication Helper Functions
 */

// Check if user is logged in as member
function checkLogin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
        redirect('login.php', 'Silakan login terlebih dahulu', 'error');
    }
}

// Check if user is logged in as admin
function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        redirect('login.php', 'Akses ditolak. Silakan login sebagai admin', 'error');
    }
}

// Check if user is logged in (any role)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user data
function getCurrentUser() {
    global $conn;
    
    if (!isLoggedIn()) {
        return null;
    }
    
    $role = $_SESSION['role'];
    $user_id = $_SESSION['user_id'];
    
    if ($role === 'admin') {
        $query = "SELECT * FROM owner WHERE id_owner = '$user_id'";
    } else {
        $query = "SELECT * FROM member WHERE id_member = '$user_id'";
    }
    
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Check user permission
function hasPermission($permission) {
    if (!isLoggedIn()) {
        return false;
    }
    
    // Admin has all permissions
    if ($_SESSION['role'] === 'admin') {
        return true;
    }
    
    // Add more permission logic here if needed
    return false;
}

// Log user activity
function logActivity($user_id, $activity, $details = '') {
    global $conn;
    
    $query = "INSERT INTO activity_log (user_id, activity, details, created_at) 
              VALUES ('$user_id', '" . escape($activity) . "', '" . escape($details) . "', NOW())";
    
    return mysqli_query($conn, $query);
}
?>