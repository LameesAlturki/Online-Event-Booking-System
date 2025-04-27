    <?php
    require_once 'config.php';

    //check if user is logged in and is admin
    if (!isLoggedIn() || !isAdmin()) {
      redirect('admin.php');
    }

    // get all events
    $query = "SELECT * FROM events";
    $result = $conn->query($query);
    $events = [];

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $events[] = $row;
      }
    }

    // Check for messages
    $message = $_SESSION['message'] ?? '';
    unset($_SESSION['message']);
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Manage Events - Admin</title>
      <?php include 'styles.php'; ?>
    </head>

    <body class="w3-sand w3-large">
      <!-- Side Menu -->
      <?php include 'admin_menu.php'; ?>

      <!-- Main Content -->
      <div class="main-content">
        <h1 class="w3-xxlarge w3-text-black">Manage Events</h1>

        <?php if ($message): ?>
          <div class="w3-panel w3-green w3-padding w3-round-large w3-card"><?= $message ?></div>
        <?php endif; ?>

        <?php if (empty($events)): ?>
          <div class="w3-panel w3-pale-red w3-border w3-bold">
            No events available. <a href="addEvent.php" class="w3-text-brown">Add an event</a> to get started.
          </div>
        <?php else: ?>
          <div class="w3-card w3-round-large w3-white w3-padding">
            <table class="w3-table w3-bordered w3-striped w3-white">
              <thead>
                <tr class="w3-brown w3-text-white">
                  <th>Event Name</th>
                  <th>Date</th>
                  <th>Location</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($events as $event): ?>
                  <tr>
                    <td><?= htmlspecialchars($event['name']) ?></td>
                    <td><?= date('Y-m-d', strtotime($event['date_time'])) ?></td>
                    <td><?= htmlspecialchars($event['location']) ?></td>
                    <td>
                      <a href="viewEvent.php?id=<?= $event['id'] ?>">View</a>
                      <a href="editEvent.php?id=<?= $event['id'] ?>">Edit</a>
                      <a href="deleteEvent.php?id=<?= $event['id'] ?>">Delete</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </body>

    </html>