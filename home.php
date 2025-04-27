<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('index.php');
}

// Check if user is admin and redirect if needed
if (isAdmin()) {
    redirect('manageEvents.php');
}

// Get all events that havent passed and have available tickets
$query = "SELECT * FROM events WHERE date_time > NOW() AND remaining_tickets > 0 ORDER BY date_time";
$result = $conn->query($query);
$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Get cart count
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
    <title>Home - Event Booking System</title>
    <?php include 'styles.php'; ?>

</head>

<body class="w3-sand">
    <!-- Header -->
    <header class="w3-bar site-header w3-padding w3-card-4">
        <div class="w3-bar-item w3-xlarge">
            <img src="outline-star-64.png" style="height: 30px; margin-right: 10px;">
            Event Booking System
        </div>
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
        <h2 class="w3-center w3-margin-top">Upcoming Events</h2>

        <?php if (empty($events)): ?>
            <p class="w3-center w3-text-grey">No upcoming events available at the moment.</p>
        <?php else: ?>
            <div class="events-grid w3-padding">
                <?php foreach ($events as $event): ?>
                    <div class="event-card w3-card-4">
                        <div class="event-imageh">
                            <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['name']) ?>">
                        </div>
                        <div class="event-details w3-center">
                            <h3><?= htmlspecialchars($event['name']) ?></h3>
                            <p class="w3-text-grey"><?= date('M d, Y h:i A', strtotime($event['date_time'])) ?></p>
                            <p class="w3-large w3-text-theme">SAR <?= number_format($event['price'], 2) ?></p>
                        </div>
                        <a href="event.php?id=<?= $event['id'] ?>" class="btn-bookNow">Book Now</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="w3-container site-footer w3-center w3-padding-16 w3-margin-top">
        <p>&copy; <?= date('Y') ?> Event Booking System. All rights reserved.</p>
    </footer>
</body>

</html>