<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('index.php');
}

$event_id = intval($_GET['id'] ?? 0);

//get event details
$query = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

$event = $result->fetch_assoc();

$errors = [];

//form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($quantity <= 0) {
        $errors[] = 'Please select at least 1 ticket.';
    }
    if ($quantity > $event['remaining_tickets']) {
        $errors[] = 'Only ' . $event['remaining_tickets'] . ' tickets available.';
    }

    if (empty($errors)) {
        //reset the cart if they're booking a new event
        $_SESSION['cart'] = [
            'event_id' => $event_id,
            'event_name' => $event['name'],
            'event_date' => $event['date_time'],
            'ticket_price' => $event['price'],
            'quantity' => $quantity,
            'total_price' => $quantity * $event['price'],
            'image_path' => $event['image']
        ];
    }
}

$cartCount = 0;
if (isset($_SESSION['cart']['quantity'])) {
    $cartCount = (int)$_SESSION['cart']['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['name']) ?> - Event Booking System</title>
    <?php include 'styles.php'; ?>
</head>

<body class="w3-sand">
    <!-- Header -->
    <header class="w3-bar site-header w3-padding w3-card-4">
        <div class="w3-bar-item w3-xlarge">Event Booking System</div>
        <div class="w3-bar-item w3-right w3-hide-small">
            <span class="w3-margin-right">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="cart.php" class="w3-button w3-olive w3-border w3-round-large">
                Cart
                <?php if ($cartCount > 0): ?>
                    <span class="cart-count"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <a href="logout.php" class="w3-button w3-red w3-border w3-round-large w3-margin-left">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="w3-content w3-padding" style="max-width: 1200px;">
        <div class="w3-card-4 w3-round-large w3-padding w3-margin-top">

            <div class="w3-center">
                <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['name']) ?>" style="width:100%; max-width:600px; border-radius:12px;">
            </div>

            <div class="w3-container w3-padding">
                <h2 class="w3-center"><?= htmlspecialchars($event['name']) ?></h2>

                <p><strong>Date & Time:</strong> <?= date('F j, Y, g:i a', strtotime($event['date_time'])) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                <p><strong>Ticket Price:</strong> $<?= number_format($event['price'], 2) ?></p>
                <p><strong>Available Tickets:</strong> <?= $event['remaining_tickets'] ?></p>

                <?php if (!empty($errors)): ?>
                    <div class="w3-panel w3-pale-red w3-border w3-border-red w3-round-large">
                        <ul class="w3-ul">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="w3-container w3-margin-top">
                    <label for="quantity" class="w3-text-theme"><b>Number of Tickets:</b></label>
                    <input class="w3-input w3-border w3-round-large" type="number" id="quantity" name="quantity" min="1" max="<?= $event['remaining_tickets'] ?>" value="1" required>

                    <button type="submit" class="w3-button w3-green w3-margin-top w3-round-large">Add to Cart</button>
                </form>
                </form>
                <form action="home.php" class="w3-center w3-margin-top">
                    <button type="submit" class="w3-button w3-brown w3-large w3-round-large">Back home</button>
                </form>

            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="w3-container site-footer w3-center w3-padding-16 w3-margin-top">
        <p>&copy; <?= date('Y') ?> Event Booking System. All rights reserved to us!!!!</p>
    </footer>
</body>

</html>