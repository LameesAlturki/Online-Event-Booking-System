<?php
require_once 'config.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('admin.php');
}

$event_id = $_GET['id'];
$errors = [];

// Get event details
$query = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

$event = $result->fetch_assoc();

//check if there are bookings for this event
$query = "SELECT COUNT(*) as count FROM bookings WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$booking_count = $result->fetch_assoc()['count'];

// form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if there are bookings
    if ($booking_count > 0) {
        $errors[] = 'Cannot delete an event that has bookings. There are ' . $booking_count . ' bookings for this event.';
    } else {
        // Delete the event image 
        if (file_exists('uploads/' . $event['image'])) {
            unlink('uploads/' . $event['image']);
        }
        // Delete the event
        $query = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Event deleted successfully';
            redirect('manageEvents.php');
        } else {
            $errors[] = 'Error deleting event: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event - Admin</title>
    <?php include 'styles.php'; ?>

</head>

<body class="w3-sand">
    <div class="admin-container">
        <!-- Side Menu -->
        <?php include 'admin_menu.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1>Delete Event</h1>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($booking_count > 0): ?>
                <div class="error-message">
                    <p>This event cannot be deleted because there are <?= $booking_count ?> bookings associated with it.</p>
                </div>
                <div class="action-buttons">
                    <a href="manageEvents.php" class="btn btn-secondary">Back to Events</a>
                </div>
            <?php else: ?>
                <div class="event-details">
                    <div class="event-image">
                        <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['name']) ?>">
                    </div>

                    <div class="event-info">
                        <h2><?= htmlspecialchars($event['name']) ?></h2>

                        <div class="detail-item">
                            <span class="label">Date & Time:</span>
                            <span><?= date('M d, Y h:i A', strtotime($event['date_time'])) ?></span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Location:</span>
                            <span><?= htmlspecialchars($event['location']) ?></span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Ticket Price:</span>
                            <span>SAR <?= number_format($event['price'], 2) ?></span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Maximum Tickets:</span>
                            <span><?= $event['max_tickets'] ?></span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Remaining Tickets:</span>
                            <span><?= $event['remaining_tickets'] ?></span>
                        </div>
                    </div>
                </div>

                <div class="warning-message">
                    <p>Are you sure you want to delete this event? This action cannot be undone.</p>
                </div>

                <form method="POST" action="">
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-delete">Yes, Delete this Event</button>
                        <a href="manageEvents.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>