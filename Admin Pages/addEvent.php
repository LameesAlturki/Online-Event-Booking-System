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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        <div id="phpError" class="error-message" style="display: none;">

          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= $error ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form id="eventForm" method="POST" action="" enctype="multipart/form-data" class="event-form">

        <div class="form-group">
          <label for="name">Event Name</label>
          <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" >
        </div>

        <div class="form-group">
          <label for="date_time">Event Date & Time</label>
          <input type="datetime-local" id="date_time" name="date_time" value="<?= $_POST['date_time'] ?? '' ?>">
        </div>

        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="price">Ticket Price (SAR)</label>
          <input type="number" step="0.1" min="0" id="price" name="price" value="<?= $_POST['price'] ?? '' ?>">
        </div>

        <div class="form-group">
          <label for="image">Event Image</label>
          <input type="file" id="image" name="image">
        </div>

        <div class="form-group">
          <label for="max_tickets">Maximum Tickets</label>
          <input type="number" id="max_tickets" name="max_tickets" min="1" value="<?= $_POST['max_tickets'] ?? '' ?>">
        </div>

        <div class="action-buttons">
          <button type="submit" class="btn btn-add">Add Event</button>
          <a href="manageEvents.php" class="btn-back">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('eventForm').addEventListener('submit', function(event) {
      const name = document.getElementById('name').value.trim();
      const dateTime = document.getElementById('date_time').value;
      const location = document.getElementById('location').value.trim();
      const price = document.getElementById('price').value;
      const image = document.getElementById('image').files.length;
      const maxTickets = document.getElementById('max_tickets').value;

      let errors = [];

      // Display an error message 
      function showError(id, message) {
        document.getElementById(id).textContent = message;
      }

      // Clear the error 
      function clearError(id) {
        document.getElementById(id).textContent = "";
      }

      // Clear all previous error messages
      clearError("nameError");
      clearError("dateTimeError");
      clearError("locationError");
      clearError("priceError");
      clearError("imageError");
      clearError("maxTicketsError");

      // Validate each field and show error messages
      if (name === "") {
        showError("nameError", "Event Name is required.");
      }
      if (dateTime === "") {
        showError("dateTimeError", "Event Date & Time is required.");
      }
      if (location === "") {
        showError("locationError", "Location is required.");
      }
      if (price === "" || price < 0) {
        showError("priceError", "Valid Ticket Price is required.");
      }
      if (image === 0) {
        showError("imageError", "Event Image must be uploaded.");
      }
      if (maxTickets === "" || maxTickets < 1) {
        showError("maxTicketsError", "Maximum Tickets must be at least 1.");
      }

      // If there are any errors, prevent form submission
      if (errors.length > 0) {
        event.preventDefault();
        alert(errors.join("\n"));
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      const phpErrors = document.getElementById('phpError');
      if (phpErrors) {
        phpErrors.style.display = 'block';
      }
    });
  </script>
</body>

</html>
