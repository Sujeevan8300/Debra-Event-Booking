<?php
// Include your database connection here
require_once('db_connection.php');

// Check user role (e.g., admin, partner) and user ID
// This depends on your authentication mechanism
$userRole = "admin"; // Replace with actual user role
$userId = 1; // Replace with actual user ID

// Query to fetch bookings based on user role and ID
if ($userRole === "admin") {
    // Admin can see all bookings
    $sql = "SELECT b.booking_id, e.title, b.name, b.address, b.email, b.contactNo, b.numTickets
            FROM bookings b
            INNER JOIN events e ON b.event_id = e.id";
} elseif ($userRole === "partner") {
    // Partners can only see their own bookings
    $sql = "SELECT b.booking_id, e.title, b.name, b.address, b.email, b.contactNo, b.numTickets
            FROM bookings b
            INNER JOIN events e ON b.event_id = e.id
            WHERE e.user_id = ?";
} else {
    // Handle other user roles as needed
    echo "Access denied.";
    exit();
}

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($userRole === "partner") {
        // Bind the partner's user ID
        $stmt->bind_param('i', $userId);
    }

    // Execute the statement
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($bookingId, $eventTitle, $name, $address, $email, $contactNo, $numTickets);

    // Fetch the bookings
    $bookings = array();
    while ($stmt->fetch()) {
        $bookings[] = array(
            'booking_id' => $bookingId,
            'event_title' => $eventTitle,
            'name' => $name,
            'address' => $address,
            'email' => $email,
            'contactNo' => $contactNo,
            'numTickets' => $numTickets,
        );
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle SQL statement preparation error
    echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <link rel="stylesheet" href="style_nav.css">
    <link rel="stylesheet" href="style_b_m.css">

    <!-- Add any meta tags, link tags, or external resources as needed -->
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
    <!-- Display booking data and provide CRUD operations -->
    <div class="container">
        <h1>Booking Management</h1>
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Event Title</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Number of Tickets</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) : ?>
                    <tr>
                        <td><?php echo $booking['booking_id']; ?></td>
                        <td><?php echo $booking['event_title']; ?></td>
                        <td><?php echo $booking['name']; ?></td>
                        <td><?php echo $booking['address']; ?></td>
                        <td><?php echo $booking['email']; ?></td>
                        <td><?php echo $booking['contactNo']; ?></td>
                        <td><?php echo $booking['numTickets']; ?></td>
                        <td>
                            <!-- Add CRUD action buttons (e.g., Edit and Delete) -->
                            <a href="edit_booking.php?id=<?php echo $booking['booking_id']; ?>">Edit</a>
                            <a href="delete_booking.php?id=<?php echo $booking['booking_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
