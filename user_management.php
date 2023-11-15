<?php
require_once('db_connection.php'); // Include the database connection file

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Create User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Insert user data into the database
    $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $password, $email, $role);

    if ($stmt->execute()) {
        header("Location: user_management.php");
        exit();
    } else {
        $error_message = "User creation failed. Please try again.";
    }

    $stmt->close();
}

// Read Users
$sql = "SELECT id, username, email, role FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Update User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];
    $newRole = $_POST['newRole'];

    // Update user data in the database
    $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $newUsername, $newEmail, $newRole, $user_id);

    if ($stmt->execute()) {
        header("Location: user_management.php");
        exit();
    } else {
        $error_message = "User update failed. Please try again.";
    }

    $stmt->close();
}

// Delete User
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: user_management.php");
        exit();
    } else {
        $error_message = "User deletion failed. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="style_um.css">
    <link rel="stylesheet" href="style_nav.css">
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

    <h2>User Management</h2>
    
    <!-- User Creation Form -->
    <h3>Create User</h3>
    <?php if (isset($error_message)) { ?>
        <p><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="POST" action="user_management.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="role">Role:</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="partner">Partner</option>
            <option value="customer">Customer</option>
        </select><br>

        <input type="submit" name="create_user" value="Create User">
    </form>

    <!-- User List -->
    <h3>User List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <a href="user_management.php?edit_user=<?php echo $user['id']; ?>">Edit</a> |
                    <a href="user_management.php?delete_user=<?php echo $user['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Update User Form (if editing) -->
    <?php if (isset($_GET['edit_user'])) { ?>
        <h3>Edit User</h3>
        <?php
        $edit_user_id = $_GET['edit_user'];

        // Fetch user data for editing
        $sql = "SELECT id, username, email, role FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $edit_user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
        } else {
            // User not found
            header("Location: user_management.php");
            exit();
        }
        ?>
        <form method="POST" action="user_management.php">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <label for="newUsername">New Username:</label>
            <input type="text" name="newUsername" value="<?php echo $user['username']; ?>" required><br>

            <label for="newEmail">New Email:</label>
            <input type="email" name="newEmail" value="<?php echo $user['email']; ?>" required><br>

            <label for="newRole">New Role:</label>
            <select name="newRole">
                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                <option value="partner" <?php if ($user['role'] === 'partner') echo 'selected'; ?>>Partner</option>
                <option value="customer" <?php if ($user['role'] === 'customer') echo 'selected'; ?>>Customer</option>
            </select><br>

            <input type="submit" name="update_user" value="Update User">
        </form>
    <?php } ?>
</body>
</html>
