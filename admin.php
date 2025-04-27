<?php
require_once 'config.php';

// Check if already logged in
if (isLoggedIn() && isAdmin()) {
    redirect('manageEvents.php');
}

$error = '';
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Admin credentials
    if ($username === 'admin' && $password === 'admin123') {
        // Set session
        $_SESSION['user_id'] = 1; // We are using id 1 for admin
        $_SESSION['username'] = 'Admin';
        $_SESSION['is_admin'] = 1;
        redirect('manageEvents.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Event Booking System</title>
    <?php include 'styles.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('.login-form');

            loginForm.addEventListener('submit', function(event) {
                let username = document.querySelector('input[name="username"]').value.trim();
                let password = document.querySelector('input[name="password"]').value.trim();
                let errors = [];

                // Clear previous error messages
                clearError();

                // Validate username and password
                if (username === '') {
                    errors.push('Username is required');
                    showError('Username is required');
                }

                if (password === '') {
                    errors.push('Password is required');
                    showError('Password is required');
                }
                if (password !='admin123' && username!='admin') {
                    errors.push('wrong credintials');
                    showError('Wrong Credintials');
                }

                // If there are errors, prevent form submission
                if (errors.length > 0) {
                    event.preventDefault();
                }
            });

            function showError(message) {
                const errorMessageDiv = document.createElement('div');
                errorMessageDiv.classList.add('error-message');
                errorMessageDiv.textContent = message;
                const form = document.querySelector('.login-form');
                form.prepend(errorMessageDiv);
            }

            function clearError() {
                const errorMessages = document.querySelectorAll('.error-message');
                errorMessages.forEach(function(errorMessage) {
                    errorMessage.remove();
                });
            }
        });
    </script>
</head>

<body>
    <div class="login-page">
        <form class="login-form w3-card-4" method="POST">
            <h2 class="w3-center">Admin Login</h2>

            <div class="w3-section">
                <input class="w3-input w3-border" name="username" type="text" placeholder="Username">
            </div>

            <div class="w3-section">
                <input class="w3-input w3-border" name="password" type="password" placeholder="Password">
            </div>

            <button type="submit" class="btn-primary w3-block">Login</button>
        </form>

    </div>
</body>

</html>