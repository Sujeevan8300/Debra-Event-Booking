<?php
require_once('db_connection.php'); // Include the database connection file

// Check if the user is logged in as an admin or partner
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'partner')) {
    header("Location: login.php");
    exit();
}

// Create Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $available_tickets = $_POST['available_tickets'];
    $user_id = $_SESSION['user_id']; // Get the user's ID from the session

    // Insert event data into the database, associating it with the current user
    $sql = "INSERT INTO events (title, description, date, location, price, available_tickets, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdii", $title, $description, $date, $location, $price, $available_tickets, $user_id);

    if ($stmt->execute()) {
        header("Location: events.php");
        exit();
    } else {
        $error_message = "Event creation failed. Please try again.";
    }

    $stmt->close();
}

// Read Events
$user_id = $_SESSION['user_id']; // Get the user's ID from the session
$role = $_SESSION['role']; // Get the user's role from the session

// Query events based on the user's role and ID
if ($role === 'admin') {
    $sql = "SELECT id, title, description, date, location, price, available_tickets FROM events";
} elseif ($role === 'partner') {
    $sql = "SELECT id, title, description, date, location, price, available_tickets FROM events WHERE user_id = ?";
}

$stmt = $conn->prepare($sql);

if ($role === 'partner') {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Update Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $available_tickets = $_POST['available_tickets'];

    // Update event data in the database
    $sql = "UPDATE events SET title = ?, description = ?, date = ?, location = ?, price = ?, available_tickets = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdii", $title, $description, $date, $location, $price, $available_tickets, $event_id);

    if ($stmt->execute()) {
        header("Location: events.php");
        exit();
    } else {
        $error_message = "Event update failed. Please try again.";
    }

    $stmt->close();
}

// Delete Event
if (isset($_GET['delete_event'])) {
    $event_id = $_GET['delete_event'];

    // Delete the event from the database
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        header("Location: events.php");
        exit();
    } else {
        $error_message = "Event deletion failed. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management</title>
    <link rel="stylesheet" href="style_nav.css">
    <link rel="stylesheet" href="style_pd_ev.css">
    <!-- Include your CSS stylesheets here -->
</head>
<body>
 <!-- Partner Dashboard Heading with Class Name -->
<h2 class="dashboard-heading">Welcome to the Partner Dashboard</h2>

<!-- Navigation Menu with Class Name -->
<nav class="main-menu">
    <ul>
        <li><a href="partner_dashboard.php">Dashboard</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="booking_management.php">Booking Management</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>



    
    <h1>Event Management</h1>
    
    <!-- Event Creation Form -->
    <h3>Create Event</h3>
    <?php if (isset($error_message)) { ?>
        <p><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="POST" action="events.php">
        <label for="title">Event Title:</label>
        <input type="text" name="title" required><br>

        <label for="description">Event Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="date">Event Date:</label>
        <input type="date" name="date" required><br>

        <label for="location">Event Location:</label>
        <input type="text" name="location" required><br>

        <label for="price">Event Price:</label>
        <input type="number" name="price" step="0.01" required><br>

        <label for="available_tickets">Available Tickets:</label>
        <input type="number" name="available_tickets" required><br>

        <input type="submit" name="create_event" value="Create Event">
    </form>

    <!-- Event List -->
    <h3>Event List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Location</th>
            <th>Price</th>
            <th>Available Tickets</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($events as $event) { ?>
            <tr>
                <td><?php echo $event['id']; ?></td>
                <td><?php echo $event['title']; ?></td>
                <td><?php echo $event['description']; ?></td>
                <td><?php echo $event['date']; ?></td>
                <td><?php echo $event['location']; ?></td>
                <td><?php echo $event['price']; ?></td>
                <td><?php echo $event['available_tickets']; ?></td>
                <td>
                    <a href="events.php?edit_event=<?php echo $event['id']; ?>">Edit</a> |
                    <a href="events.php?delete_event=<?php echo $event['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Update Event Form (if editing) -->
    <?php if (isset($_GET['edit_event'])) { ?>
        <h3>Edit Event</h3>
        <?php
        $edit_event_id = $_GET['edit_event'];

        // Fetch event data for editing
        $sql = "SELECT id, title, description, date, location, price, available_tickets FROM events WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $event = $result->fetch_assoc();
        } else {
            // Event not found
            header("Location: events.php");
            exit();
        }
        ?>
        <form method="POST" action="events.php">
            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
            <label for="title">Event Title:</label>
            <input type="text" name="title" value="<?php echo $event['title']; ?>" required><br>

            <label for="description">Event Description:</label>
            <textarea name="description" required><?php echo $event['description']; ?></textarea><br>

            <label for="date">Event Date:</label>
            <input type="date" name="date" value="<?php echo $event['date']; ?>" required><br>

            <label for="location">Event Location:</label>
            <input type="text" name="location" value="<?php echo $event['location']; ?>" required><br>

            <label for="price">Event Price:</label>
            <input type="number" name="price" step="0.01" value="<?php echo $event['price']; ?>" required><br>

            <label for="available_tickets">Available Tickets:</label>
            <input type="number" name="available_tickets" value="<?php echo $event['available_tickets']; ?>" required><br>

            <input type="submit" name="update_event" value="Update Event">
        </form>
    <?php } ?>
</body>
</html>
