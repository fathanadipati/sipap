<?php
// Session Configuration
session_start();

// Auto-normalize old role names to new ones on every request
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'resepsionis') $_SESSION['role'] = 'receptionist';
    if ($_SESSION['role'] === 'penghuni') $_SESSION['role'] = 'resident';
}

// Check apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check role
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit();
    }
}

// Redirect jika tidak memiliki role yang tepat
function requireRole($roles) {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit();
    }
    
    if (!in_array($_SESSION['role'], (array) $roles)) {
        header('Location: ' . BASE_URL . '/forbidden.php');
        exit();
    }
}

// Base URL
define('BASE_URL', 'http://localhost/sipap');

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>
