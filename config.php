<?php
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "Online_Event_Booking_System";
$port = 8889;

//create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

//check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Helper function to redirect
function redirect($url)
{
    header("Location: $url");
    exit();
}
// Function to check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
// Function to check if logged in user is admin
function isAdmin()
{
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}
