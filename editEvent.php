<?php
require_once 'config.php';

// check if logged in and admin
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

//form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate inputs
    $name = trim($_POST['name'] ?? '');
    $date_time = $_POST['date_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $price = $_POST['price'] ?? '';
    $max_tickets = $_POST['max_tickets'] ?? '';

    // Validation
    if (empty($name)) {
        $errors[] = 'Event name is required';
    }
    if (empty($date_time)) {
        $errors[] = 'Event date and time are required';
    }
    if (empty($location)) {
        $errors[] = 'Location is required';
    }
    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = 'Valid ticket price is required';
    }
    if (empty($max_tickets) || !is_numeric($max_tickets) || $max_tickets <= 0) {
        $errors[] = 'Valid maximum number of tickets is required';
    }

    // Check if max_tickets is not less than already booked tickets
    $booked_tickets = $event['max_tickets'] - $event['remaining_tickets'];
    if ($max_tickets < $booked_tickets) {
        $errors[] = 'Maximum tickets cannot be less than already booked tickets (' . $booked_tickets . ')';
    }

    $image = $event['image'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        // Check file type
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_extension, $allowed_types)) {
            $errors[] = 'Only JPG, JPEG, PNG files are allowed';
        }

        // Upload file if no errors
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Delete old image 
                if (file_exists('uploads/' . $event['image'])) {
                    unlink('uploads/' . $event['image']);
                }
                $image = $new_filename;
            } else {
                $errors[] = 'Error uploading file';
            }
        }
    }

    // Update event if no errors
    if (empty($errors)) {
        $remaining_tickets = $max_tickets - ($event['max_tickets'] - $event['remaining_tickets']);

        $query = "UPDATE events SET 
                  name = ?, 
                  date_time = ?, 
                  location = ?, 
                  price = ?, 
                  image = ?, 
                  max_tickets = ?, 
                  remaining_tickets = ? 
                  WHERE id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssdsiii", $name, $date_time, $location, $price, $image, $max_tickets, $remaining_tickets, $event_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Event updated successfully';
            redirect('manageEvents.php');
        } else {
            $errors[] = 'Error updating event: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin</title>
    <?php include 'styles.php'; ?>

</head>

<body class="w3-sand">
    <div class="admin-container">
        <!-- Side Menu -->
        <?php include 'admin_menu.php'; ?>
        <!-- Main Content -->
        <div class="main-content">
            <h1>Edit Event</h1>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" id="editEventForm">
                <div class="form-group">
                    <label for="name">Event Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($event['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="date_time">Event Date & Time:</label>
                    <input type="datetime-local" id="date_time" name="date_time" value="<?= date('Y-m-d\TH:i', strtotime($event['date_time'])) ?>" required>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">Ticket Price (SAR):</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?= $event['price'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="current_image">Current Image:</label>
                    <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="Current Event Image" class="thumbnail">
                </div>

                <div class="form-group">
                    <label for="image">Change Event Image (optional):</label>
                    <input type="file" id="image" name="image">
                    <small>Leave blank to keep current image</small>
                </div>

                <div class="form-group">
                    <label for="max_tickets">Maximum Number of Tickets:</label>
                    <input type="number" id="max_tickets" name="max_tickets" min="<?= $event['max_tickets'] - $event['remaining_tickets'] ?>" value="<?= $event['max_tickets'] ?>" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Event</button>
                    <a href="manageEvents.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>