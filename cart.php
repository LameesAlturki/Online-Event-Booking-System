<?php
require_once 'config.php';
date_default_timezone_set('Asia/Riyadh');

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('index.php');
}

//initialize cart if not already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$totalAmount = $cart ? $cart['total_price'] : 0;
$currentDateTime = date('M d, Y h:i A');

$successMessage = '';

// get event details if there is one
$event = null;
if (!empty($cart['event_id'])) {
    $eventId = $cart['event_id'];
    $stmt = $conn->prepare("SELECT name, date_time FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
}

//Reserve Tickets POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve_tickets'])) {
    if (!empty($cart)) {
        $userId = $_SESSION['user_id'];
        $quantity = $cart['quantity'];
        $totalPrice = $cart['total_price'];
        $bookingDate = date('Y-m-d H:i:s');

        $conn->begin_transaction();
        try {
            // Insert reservation into bookings
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, num_tickets, total_price, booking_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiids", $userId, $eventId, $quantity, $totalPrice, $bookingDate);
            $stmt->execute();
            $stmt->close();

            // Update event available tickets
            $stmt = $conn->prepare("UPDATE events SET remaining_tickets = remaining_tickets - ? WHERE id = ? AND remaining_tickets >= ?");
            $stmt->bind_param("iii", $quantity, $eventId, $quantity);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception('Not enough tickets available.');
            }

            $stmt->close();
            $conn->commit();

            // Clear cart when booking is completed
            unset($_SESSION['cart']);
            $successMessage = "You completed your booking successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            $successMessage = "Error processing booking: " . $e->getMessage();
        }
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
    <title>Cart - Event Booking System</title>
    <?php include 'styles.php'; ?>
</head>

<body class="w3-sand">
    <div class="page-container">
        <!-- Header -->
        <header class="w3-bar site-header w3-padding w3-card-4">
            <div class="w3-bar-item w3-xlarge">Event Booking System</div>
            <div class="w3-bar-item w3-right w3-hide-small">
                <span class="w3-margin-right">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="cart.php" class="w3-button w3-olive w3-border w3-round-large">
                    Cart
                    <?php if (!empty($cart)): ?>
                        <span class="cart-count"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="logout.php" class="w3-button w3-red w3-border w3-round-large w3-margin-left">Logout</a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="w3-container w3-padding-32">
            <h2 class="w3-center">Your Cart</h2>

            <?php if (!empty($successMessage)): ?>
                <div class="w3-panel w3-green w3-padding w3-round-large w3-center w3-animate-opacity">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <div class="w3-center w3-margin-bottom">
                <strong>Current Date & Time:</strong> <?= $currentDateTime ?>
            </div>

            <?php if (empty($cart)): ?>
                <div class="w3-panel w3-pale-red w3-border w3-bold">
                    Your cart is empty. <a href="home.php" class="w3-text-brown">Browse events</a> to add tickets.
                </div>
            <?php else: ?>
                <div class="w3-card-4 w3-white w3-padding">

                    <table class="w3-table w3-striped">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>Event Name</th>
                                <th>Event Date</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($event['name']) ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($event['date_time'])) ?></td>
                                <td><?= $cart['quantity'] ?></td>
                                <td>SAR <?= number_format($cart['total_price'], 2) ?></td>
                            </tr>
                        </tbody>

                    </table>

                    <div class="w3-right-align w3-margin-top">
                        <strong>Total Price:</strong> SAR<?= number_format($totalAmount, 2) ?>
                    </div>

                    <form action="cart.php" method="POST" class="w3-center w3-margin-top">
                        <button type="submit" name="reserve_tickets" class="w3-button w3-green w3-large w3-round-large">Reserve Tickets</button>

                    </form>
                    <form action="home.php" class="w3-center w3-margin-top">
                        <button type="submit" class="w3-button w3-brown w3-large w3-round-large">Back home</button>
                    </form>

                </div>
            <?php endif; ?>
        </main>
        <!-- Footer -->
        <footer class="w3-container site-footer w3-center w3-padding-16 w3-margin-top">
            <p>&copy; <?= date('Y') ?> Event Booking System. All rights reserved to us!!!!</p>
        </footer>
    </div>
</body>

</html>