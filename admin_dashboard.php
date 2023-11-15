<?php
// Include db_connection.php for database connection
require_once('db_connection.php');

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect unauthorized users to the login page
    exit();
}

// You can add more admin-specific functionality here, such as event management or user management
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style_ad.css">
    <link rel="stylesheet" href="style_nav.css">
    <script src="script_ad.js"></script>
    <!-- Include your CSS stylesheets here -->
</head>
<body>
    <!-- Partner Dashboard Heading with Class Name -->
<h2 class="dashboard-heading">Welcome to the Admin Dashboard</h2>

<!-- Navigation Menu with Class Name -->
<nav class="main-menu">
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="event_management.php">Event Management</a></li>
        <li><a href="user_management.php">User Management</a></li>
        <li><a href="admin_managebookings.php">Booking Management</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

    <!-- Admin-specific content goes here -->
    <div>
        <h2>Event Management</h2>
        <!-- Add event management functionality and listings here -->
    </div>

    <div>
        <h2>User Management</h2>
        <!-- Add user management functionality and listings here -->
    </div>

    <!-- You can add more sections for other admin tasks as needed -->

    <!-- Include your JavaScript scripts here -->

</body>
</html>
