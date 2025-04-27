<?php
require_once 'config.php';

//check if already logged in
if (isLoggedIn() && isAdmin()) {
    redirect('manageEvents.php');
}

$error = '';
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    //admin credentials
    if ($username === 'admin' && $password === 'admin123') {
        // Set session
        $_SESSION['user_id'] = 1; // we are using id 1 for admin
        $_SESSION['username'] = 'Admin';
        $_SESSION['is_admin'] = 1;
        redirect('manageEvents.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<?php if (!isset($error)) $error = null; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Event Booking System</title>
    <?php include 'styles.php'; ?>
</head>

<div class="login-page">
    <form class="login-form w3-card-4" method="POST">
        <h2 class="w3-center">Admin Login</h2>

        <div class="w3-section">
            <input class="w3-input w3-border" name="username" type="text" placeholder="Username" required>
        </div>

        <div class="w3-section">
            <input class="w3-input w3-border" name="password" type="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn-primary w3-block">Login</button>
    </form>
</div>
</html>