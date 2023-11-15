<?php
// Check if the user is logged in as a partner
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'partner') {
    header("Location: login.php");
    exit();
}

// You can include any partner-specific logic here
?>

<!DOCTYPE html>
<html>
<head>
    <title>Partner Dashboard</title>
    <link rel="stylesheet" href="style_pd.css">
    <!-- Include your CSS stylesheets here -->
</head>
<body>
    <h2>Welcome to the Partner Dashboard</h2>
    
    <!-- Navigation Links -->
    <ul>
        <li><a href="partner_dashboard.php">Dashboard</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="booking_management.php">Booking Management</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>


    <!-- Partner-specific content can be added here -->
    <p>Partner-specific content goes here.</p>
    
</body>
</html>
