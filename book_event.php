<?php
// Include your database connection here
require_once('db_connection.php');

// Initialize variables to store event details
$title = $date = $location = $price = '';

// Check if the event_id is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize and retrieve the event_id from the URL
    $event_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Query to fetch event details based on event_id
    $sql = "SELECT title, date, location, price FROM events WHERE id = ?";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the event_id parameter
        $stmt->bind_param('i', $event_id);

        // Execute the statement
        $stmt->execute();

        // Bind the result variables
        $stmt->bind_result($title, $date, $location, $price);

        // Fetch the event details
        $stmt->fetch();

        // Close the statement
        $stmt->close();
    } else {
        // Handle SQL statement preparation error
        echo "Error: " . $conn->error;
    }
} else {
    // Handle event_id not provided in the URL
    echo "Event ID not provided.";
}

// Handle form submission for booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user information from the form
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contactNo = $_POST['contactNo'];
    $numTickets = $_POST['numTickets'];

    // Prepare and execute an SQL INSERT statement to add the booking
    $insertSql = "INSERT INTO bookings (event_id, name, address, email, contactNo, numTickets) VALUES (?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertSql);

    if ($insertStmt) {
        // Bind the parameters
        $insertStmt->bind_param('issssi', $event_id, $name, $address, $email, $contactNo, $numTickets);

        // Execute the statement
        $insertStmt->execute();

        // Close the statement
        $insertStmt->close();

        // Assuming booking is successful, show a "Booking Successful" message using JavaScript toast
        echo "<script>alert('Booking Successful');</script>";

        // Redirect to customer_dashboard.php after booking
        header("Location: customer_dashboard.php");
        exit();
    } else {
        // Handle SQL statement preparation error
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event</title>
    <link rel="stylesheet" href="style_cd_b_event.css">

    <!-- Add any meta tags, link tags, or external resources as needed -->
</head>
<body>
    <!-- Display event details for booking -->
    <div class="container">
        <h1>Book Event: <?php echo $title; ?></h1>
        <p>Date: <?php echo $date; ?></p>
        <p>Location: <?php echo $location; ?></p>
        <p>Price: Rs.<?php echo $price; ?></p>

        <!-- Booking Form -->
        <form action="" method="post">
            <h2>Your Information</h2>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <label for="contactNo">Contact No:</label>
            <input type="text" name="contactNo" id="contactNo" required><br>

            <label for="numTickets">Number of Tickets:</label>
            <input type="number" name="numTickets" id="numTickets" required><br>

            <button type="submit">Book Now</button>  
            <button type="button" onclick="window.location.href='customer_dashboard.php'">Cancel</button>
        </form>
    </div>
</body>
</html>
