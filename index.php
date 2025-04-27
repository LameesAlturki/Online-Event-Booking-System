<?php
require_once 'config.php';

$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($email) || empty($password)) {
        $error = 'All fields are required';
    } else {
        // Check credentials
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                //setting session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];

                redirect('home.php');
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Booking System</title>
    <?php include 'styles.php'; ?>

</head>

<body>
    <div class="login-page">
        <form class="login-form w3-card-4 w3-padding" method="POST" id="loginForm">
            <h2 class="w3-center">Event Booking System</h2>
            <p class="w3-center">Book your favorite events with ease</p>

            <?php if ($error): ?>
                <div class="w3-panel w3-red w3-padding"><?= $error ?></div>
            <?php endif; ?>

            <div class="w3-section">
                <input class="w3-input w3-border" type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="w3-section">
                <input class="w3-input w3-border" type="password" id="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-primary w3-block">Login</button>

            <div class="w3-center w3-margin-top">
                <p>Not a member yet? <a href="register.php">Register here</a></p>
            </div>
        </form>
    </div>
</body>

</html>