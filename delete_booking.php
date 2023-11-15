<?php
// Include your database connection here
require_once('db_connection.php');

// Check if the booking_id is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize and retrieve the booking_id from the URL
    $booking_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare and execute an SQL DELETE statement to delete the booking
    $deleteSql = "DELETE FROM bookings WHERE booking_id = ?";

    $deleteStmt = $conn->prepare($deleteSql);

    if ($deleteStmt) {
        // Bind the booking_id parameter
        $deleteStmt->bind_param('i', $booking_id);

        // Execute the statement
        $deleteStmt->execute();

        // Close the statement
        $deleteStmt->close();

        // Redirect to the booking_management.php page after deleting
        header("Location: booking_management.php");
        exit();
    } else {
        // Handle SQL statement preparation error
        echo "Error: " . $conn->error;
    }
} else {
    // Handle booking_id not provided in the URL
    echo "Booking ID not provided.";
    exit();
}

// Close the database connection
$conn->close();
?>
