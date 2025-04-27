<?php
session_start();
require_once 'config.php';
$wasAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// Only remove authentication variables, keep cart data
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['is_admin']);
// Add any other auth-related session variables you might have

// redirect based on user role
if ($wasAdmin) {
    header('Location: admin.php');
} else {
    header('Location: index.php');
}
exit;
