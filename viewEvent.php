<?php
require_once 'config.php';

//check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
  redirect('admin.php');
}

$event_id = $_GET['id'];

// get event details
$query = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

$event = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>View Event - Admin</title>
  <?php include 'styles.php'; ?>
</head>

<body class="w3-sand">
  <div class="admin-container">
    <!-- Side Menu -->
    <?php include 'admin_menu.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
      <h1>Event Details</h1>

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

      <div class="action-buttons">
        <a href="editEvent.php?id=<?= $event['id'] ?>" class="btn-edit">Edit Event</a>
        <a href="manageEvents.php" class="btn-back">Back to Events</a>
      </div>
    </div>
  </div>

</body>

</html>