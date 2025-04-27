<?php
session_start();
require_once 'config.php';

$wasAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// Remove all session variables
session_unset();
// Destroy the session itself
session_destroy();
// redirect based on user role
if ($wasAdmin) {
    header('Location: admin.php');
} else {
    header('Location: index.php');
}
exit;
