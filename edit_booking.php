<?php
// Include your database connection here
require_once('db_connection.php');

// Check if the booking_id is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize and retrieve the booking_id from the URL
    $booking_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Query to fetch booking details based on booking_id
    $sql = "SELECT b.booking_id, e.title, b.name, b.address, b.email, b.contactNo, b.numTickets
            FROM bookings b
            INNER JOIN events e ON b.event_id = e.id
            WHERE b.booking_id = ?";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the booking_id parameter
        $stmt->bind_param('i', $booking_id);

        // Execute the statement
        $stmt->execute();

        // Bind the result variables
        $stmt->bind_result($bookingId, $eventTitle, $name, $address, $email, $contactNo, $numTickets);

        // Fetch the booking details
        $stmt->fetch();

        // Close the statement
        $stmt->close();
    } else {
        // Handle SQL statement preparation error
        echo "Error: " . $conn->error;
    }
} else {
    // Handle booking_id not provided in the URL
    echo "Booking ID not provided.";
    exit();
}

// Handle form submission for updating booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated booking information from the form
    $updatedName = $_POST['name'];
    $updatedAddress = $_POST['address'];
    $updatedEmail = $_POST['email'];
    $updatedContactNo = $_POST['contactNo'];
    $updatedNumTickets = $_POST['numTickets'];

    // Prepare and execute an SQL UPDATE statement to update the booking
    $updateSql = "UPDATE bookings SET name = ?, address = ?, email = ?, contactNo = ?, numTickets = ? WHERE booking_id = ?";

    $updateStmt = $conn->prepare($updateSql);

    if ($updateStmt) {
        // Bind the parameters
        $updateStmt->bind_param('ssssii', $updatedName, $updatedAddress, $updatedEmail, $updatedContactNo, $updatedNumTickets, $booking_id);

        // Execute the statement
        $updateStmt->execute();

        // Close the statement
        $updateStmt->close();

        // Redirect to the booking_management.php page after updating
        header("Location: booking_management.php");
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
    <title>Edit Booking</title>
    <link rel="stylesheet" href="style_b_m.css">

    <!-- Add any meta tags, link tags, or external resources as needed -->
</head>
<body>
    <!-- Display booking details and provide a form for editing -->
    <div class="container">
        <h1>Edit Booking</h1>
        <form action="" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $name; ?>" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="<?php echo $address; ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $email; ?>" required><br>

            <label for="contactNo">Contact No:</label>
            <input type="text" name="contactNo" id="contactNo" value="<?php echo $contactNo; ?>" required><br>

            <label for="numTickets">Number of Tickets:</label>
            <input type="number" name="numTickets" id="numTickets" value="<?php echo $numTickets; ?>" required><br>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
