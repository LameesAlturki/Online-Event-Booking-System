<?php
require_once 'config.php';

// If user is already logged in, redirect to home page
/*if (isLoggedIn()) {
    redirect('home.php');
}*/

$errors = [];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    // Check if email already exists
    if (empty($errors)) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = 'Email already in use';
        }
    }

    // Register user if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Registration successful! Please login.';
            redirect('index.php');
        } else {
            $errors[] = 'Registration failed: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event Booking System</title>
    <?php include 'styles.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.querySelector('#registerForm');

            registerForm.addEventListener('submit', function(event) {
                let name = document.querySelector('input[name="name"]').value.trim();
                let email = document.querySelector('input[name="email"]').value.trim();
                let password = document.querySelector('input[name="password"]').value.trim();
                let confirmPassword = document.querySelector('input[name="confirm_password"]').value.trim();
                let errors = [];

                // Clear previous error messages
                clearError();

                // Validate name, email, password, and confirm password
                if (name === '') {
                    errors.push('Name is required');
                    showError('Name is required');
                }

                if (email === '') {
                    errors.push('Email is required');
                    showError('Email is required');
                }

                // Additional email format validation
                if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    errors.push("Please enter a valid email address.");
                    showError("Please enter a valid email address.");
                }

                if (password === '') {
                    errors.push('Password is required');
                    showError('Password is required');
                }

                if (confirmPassword === '') {
                    errors.push('Confirm password is required');
                    showError('Confirm password is required');
                }

                // Check if password and confirm password match
                if (password !== confirmPassword) {
                    errors.push('Passwords do not match');
                    showError('Passwords do not match');
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
                const form = document.querySelector('#registerForm');
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

<div class="login-page">
    <form class="login-form w3-card-4 w3-padding" method="POST" id="registerForm">
        <h2 class="w3-center">Create an Account</h2>
        <p class="w3-center">Join us to book your favorite events</p>

        <?php if (!empty($errors)): ?>
            <div class="w3-panel w3-red w3-padding w3-round-large">
                <ul class="w3-ul">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="w3-section">
            <input class="w3-input w3-border" type="text" id="name" name="name" placeholder="Name"
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="w3-section">
            <input class="w3-input w3-border" type="email" id="email" name="email" placeholder="Email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="w3-section">
            <input class="w3-input w3-border" type="password" id="password" name="password" placeholder="Password">
        </div>

        <div class="w3-section">
            <input class="w3-input w3-border" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
        </div>

        <button type="submit" class="btn-primary w3-block">Register</button>

        <div class="w3-center w3-margin-top">
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </form>
</div>

</html>