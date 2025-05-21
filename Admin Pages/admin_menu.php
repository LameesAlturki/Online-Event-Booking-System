<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
</head>
<style>
  body {
    font-size: 18px;
  }

  .sidebar {
    height: 100vh;
    width: 220px;
    background-color: #a1887f;
    position: fixed;
    padding-top: 20px;
    color: white;
  }

  .sidebar h4 {
    font-weight: bold;
    color: #fff;
  }

  .sidebar a {
    display: block;
    color: #fff8e1;
    padding: 12px 16px;
    text-decoration: none;
    border-radius: 8px;
  }

  .sidebar a:hover {
    background-color: #8d6e63;
  }
</style>
<!-- Sidebar -->
<div class="sidebar w3-padding-large">
  <h4 class="w3-center">Admin Panel</h4>
  <a href="manageEvents.php">Manage Events</a>
  <a href="addEvent.php">Add Event</a>
  <a href="viewBookings.php">View Bookings</a>
  <a href="logout.php">Logout</a>
</div>
