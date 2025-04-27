<?php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('admin.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $date_time = $_POST['date_time'] ?? ''; 
    $location = trim($_POST['location'] ?? '');
    $price = $_POST['price'] ?? '';
    $max_tickets = $_POST['max_tickets'] ?? '';

    if (empty($name)) $errors[] = 'Event name is required';
    if (empty($date_time)) {
      $errors[] = 'Event date and time are required';
  } else {
      $eventTimestamp = strtotime($date_time);
      if ($eventTimestamp < time()) {
          $errors[] = 'Event date and time must be in the future';
      }
  }
    if (empty($location)) $errors[] = 'Location is required';
    if (empty($price) || !is_numeric($price) || $price <= 0) $errors[] = 'Valid ticket price is required';
    if (empty($max_tickets) || !is_numeric($max_tickets) || $max_tickets <= 0) $errors[] = 'Valid maximum number of tickets is required';

    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) $errors[] = 'Only JPG, JPEG, PNG & GIF files are allowed';
        if ($_FILES["image"]["size"] > 2000000) $errors[] = 'File is too large (max 2MB)';

        if (empty($errors)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $new_filename;
            } else {
                $errors[] = 'Error uploading file';
            }
        }
    } else {
        $errors[] = 'Event image is required';
    }

    if (empty($errors)) {
        $query = "INSERT INTO events (name, date_time, location, price, image, max_tickets, remaining_tickets) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $remaining_tickets = $max_tickets;
        $stmt->bind_param("sssdsis", $name, $date_time, $location, $price, $image, $max_tickets, $remaining_tickets);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Event added successfully';
            redirect('manageEvents.php');
        } else {
            $errors[] = 'Error adding event: ' . $conn->error;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Event - Admin</title>
  <?php include 'styles.php'; ?>  
</head>

<body class="w3-sand">

<div class="admin-container">
  <!-- Side Menu -->
  <?php include 'admin_menu.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <h1>Add New Event</h1>

    <?php if (!empty($errors)): ?>
      <div class="error-message">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="event-form">

      <div class="form-group">
        <label for="name">Event Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label for="date_time">Event Date & Time</label>
        <input type="datetime-local" id="date_time" name="date_time" value="<?= $_POST['date_time'] ?? '' ?>" required>
      </div>

      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label for="price">Ticket Price (SAR)</label>
        <input type="number" step="0.1" min="0" id="price" name="price" value="<?= $_POST['price'] ?? '' ?>" required>
      </div>

      <div class="form-group">
        <label for="image">Event Image</label>
        <input type="file" id="image" name="image" required>
      </div>

      <div class="form-group">
        <label for="max_tickets">Maximum Tickets</label>
        <input type="number" id="max_tickets" name="max_tickets" min="1" value="<?= $_POST['max_tickets'] ?? '' ?>" required>
      </div>

      <div class="action-buttons">
        <button type="submit" class="btn btn-add">Add Event</button>
        <a href="manageEvents.php" class="btn-back">Cancel</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>

