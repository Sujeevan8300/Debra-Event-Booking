<?php
require_once('db_connection.php'); // Include the database connection file

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables
$event_id = 0;
$title = '';
$description = '';
$date = '';
$location = '';
$price = '';
$available_tickets = '';

// Function to fetch events and partner usernames
function fetchEventsWithUsernames($conn) {
    $sql = "SELECT e.id, e.title, e.description, e.date, e.location, e.price, e.available_tickets, u.username
            FROM events e
            JOIN users u ON e.user_id = u.id";

    $result = $conn->query($sql);

    $events = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

// Create Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $available_tickets = $_POST['available_tickets'];
    $user_id = $_POST['user_id']; // Partner's user_id

    $sql = "INSERT INTO events (title, description, date, location, price, available_tickets, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdii", $title, $description, $date, $location, $price, $available_tickets, $user_id);

    if ($stmt->execute()) {
        header("Location: event_management.php");
        exit();
    } else {
        $error_message = "Event creation failed. Please try again.";
    }

    $stmt->close();
}

// Read Events with Partner Username
$events = fetchEventsWithUsernames($conn);

// Update Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $available_tickets = $_POST['available_tickets'];

    $sql = "UPDATE events
            SET title = ?, description = ?, date = ?, location = ?, price = ?, available_tickets = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdii", $title, $description, $date, $location, $price, $available_tickets, $event_id);

    if ($stmt->execute()) {
        header("Location: event_management.php");
        exit();
    } else {
        $error_message = "Event update failed. Please try again.";
    }

    $stmt->close();
}

// Delete Event
if (isset($_GET['delete_event'])) {
    $event_id = $_GET['delete_event'];

    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        header("Location: event_management.php");
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
    <link rel="stylesheet" href="style_em.css">
    <!-- Include your CSS stylesheets here -->
</head>
<body>
     <!-- Admin Dashboard Heading with Class Name -->
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

    <h2>Event Management</h2>

    <!-- Event Creation Form -->
    <h3>Create Event</h3>
    <?php if (isset($error_message)) { ?>
        <p><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="POST" action="event_management.php">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
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
            <?php if ($_SESSION['role'] === 'admin') { ?>
                <th>Partner Username</th>
            <?php } ?>
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
                <?php if ($_SESSION['role'] === 'admin') { ?>
                    <td><?php echo $event['username']; ?></td>
                <?php } ?>
                <td>
                    <a href="event_management.php?edit_event=<?php echo $event['id']; ?>">Edit</a> |
                    <a href="event_management.php?delete_event=<?php echo $event['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Update Event Form (if editing) -->
    <?php if (isset($_GET['edit_event'])) { ?>
        <h3>Edit Event</h3>
        <?php
        $edit_event_id = $_GET['edit_event'];

        $sql = "SELECT id, title, description, date, location, price, available_tickets FROM events WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $event = $result->fetch_assoc();
        } else {
            header("Location: event_management.php");
            exit();
        }
        ?>
        <form method="POST" action="event_management.php">
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
