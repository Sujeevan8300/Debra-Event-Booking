<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Dashboard</title>
    <link rel="stylesheet" href="style_cust.css">

    <!-- Add any meta tags, link tags, or external resources as needed -->
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="container">
            <h1>Debra</h1>
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#events">Events</a></li>
                <li><a href="#partners">Partners</a></li>
                <li><a href="#profile">Your Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <h2>Welcome To Debra, </h2>
            <p>Your one-stop destination for event bookings and more!</p>
            <!-- Add any call-to-action buttons or links here -->
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <hr>
            <h3>About Us</h3>
            <p><i>Welcome to Debra – Your Gateway to Unforgettable Events!</i></p>
            <p>At Debra, we specialize in creating extraordinary experiences through world-class musical shows featuring the finest musicians and bands from around the globe. With a passion for music, a love for live performances, and a commitment to excellence, we've earned our reputation as a renowned event management company based in the vibrant city of Singapore.</p>

            <p><b><i>Our Story</i></b></p>
            <p>Founded by a group of music enthusiasts, Debra was born out of a shared vision to bring the magic of live music to audiences near and far. Over the years, our journey has been one of innovation, creativity, and a deep appreciation for the art of music. What began as a humble endeavor has evolved into a dynamic force in the world of event management.

            <p><b><i>Our Mission</i></b></p>
            <p>At the heart of Debra's mission is the desire to create memorable moments that resonate with audiences of all backgrounds. We believe that music has the power to connect, inspire, and uplift, and our goal is to craft events that leave a lasting impression. Whether it's an intimate jazz performance or a massive rock concert, we strive to curate experiences that touch the soul.</p>
            <i>
                <br>Email: <b>DebraEVE@gmail.com</b>
                <br>Contact NO:<b> 021 202 4444</b>
            </i>
            <hr>
        </div>
    </section>

    <!-- Events Section -->
<section id="events" class="events">
    <div class="container">
        <h3>Our Events</h3>
        <table class="event-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include your database connection here
                require_once('db_connection.php');

                // Query to fetch event data from the database
                $sql = "SELECT id, title, description, date, location, price FROM events";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>Rs." . $row['price'] . "</td>";
                        echo "<td><a href='book_event.php?id=" . $row['id'] . "' class='book-button'>Book Now</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No events found.</td></tr>";
                }

                
                ?>
            </tbody>
        </table>
    </div>
</section>


    <!-- Partners Section -->
    <section id="partners" class="partners">
        <div class="container">
            <hr>
            <h3>Our Partners</h3>
            <div class="partner-grid">
               <?php
               // Include your database connection here
               require_once('db_connection.php');

               // Query to fetch usernames of partner role users
               $sql = "SELECT username FROM users WHERE role = 'partner'";
               $result = $conn->query($sql);

               if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                    echo "<div class='partner'>$row[username]</div>";
                }
               } else {
                     echo "No partners found.";
               }

                 // Close the database connection
                 $conn->close();
                ?>
            </div>
            <hr>
        </div>
    </section>


    <!-- Profile Section -->
    <section id="profile" class="profile">
        <div class="container">
            <h3>Your Profile</h3>
            <!-- Display customer's profile information here -->
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Debra</p>
        </div>
    </footer>
</body>
</html>
