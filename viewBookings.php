<?php
require_once 'config.php';

// Check if user is loggedin and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('admin.php');
}

// Get all bookings
$query = "SELECT b.*, u.name as customer_name, u.email as customer_email, e.name as event_name, e.date_time as event_date 
          FROM bookings b
          JOIN users u ON b.user_id = u.id 
          JOIN events e ON b.event_id = e.id";


$result = $conn->query($query);
$bookings = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - Admin</title>
    <?php include 'styles.php'; ?>
</head>

<body>
    <div class="admin-container">
        <!-- Side Menu -->
        <?php include 'admin_menu.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <h1>View Bookings</h1>

            <?php if (empty($bookings)): ?>
                <p>No bookings available.</p>
            <?php else: ?>
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Booking Date</th>
                            <th>Event Name</th>
                            <th>Event Date</th>
                            <th>Number of Tickets</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                                <td><?= htmlspecialchars($booking['customer_email']) ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($booking['booking_date'])) ?></td>
                                <td><?= htmlspecialchars($booking['event_name']) ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($booking['event_date'])) ?></td>
                                <td><?= $booking['num_tickets'] ?></td>
                                <td>SAR <?= number_format($booking['total_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>