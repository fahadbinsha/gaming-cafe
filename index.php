<?php
require_once 'includes/db_connect.php';

// Get statistics
$total_customers = $conn->query("SELECT COUNT(*) as count FROM customers")->fetch_assoc()['count'];
$total_stations = $conn->query("SELECT COUNT(*) as count FROM gaming_stations")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$available_stations = $conn->query("SELECT COUNT(*) as count FROM gaming_stations WHERE status = 'Available'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameZone Elite - Premium Gaming Cafe</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎮 GAMEZONE ELITE 🎮</h1>
            <p>Premium Gaming Experience Awaits</p>
        </header>

        <nav>
            <ul>
                <li><a href="index.php">🏠 Home</a></li>
                <li><a href="customers/add_customer.php">➕ Add Customer</a></li>
                <li><a href="customers/view_customers.php">👥 View Customers</a></li>
                <li><a href="stations/view_stations.php">🖥️ Gaming Stations</a></li>
                <li><a href="bookings/add_booking.php">📝 Book Now</a></li>
                <li><a href="bookings/view_bookings.php">📋 View Bookings</a></li>
            </ul>
        </nav>

        <div class="content">
            <h2>Dashboard Overview</h2>
            
            <div class="stats-container">
                <div class="stat-card">
                    <h3><?php echo $total_customers; ?></h3>
                    <p>Total Gamers</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_stations; ?></h3>
                    <p>Gaming Stations</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $available_stations; ?></h3>
                    <p>Available Now</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_bookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>

            <div class="card">
                <h3>About GameZone Elite</h3>
                <p style="line-height: 1.8; color: var(--text-muted); font-size: 1.1em;">
                    Welcome to GameZone Elite - the ultimate gaming destination! Experience next-generation gaming with our 
                    state-of-the-art PCs featuring RTX 4090 graphics cards, PlayStation 5 and Xbox Series X consoles, 
                    immersive VR stations, and professional racing simulators. Whether you're a casual gamer or an esports 
                    enthusiast, we have the perfect setup for you.
                </p>
                <p style="line-height: 1.8; color: var(--text-muted); font-size: 1.1em; margin-top: 15px;">
                    Book your slot now and dive into the world of competitive gaming, or join our community events and 
                    tournaments. Enjoy high-speed internet, comfortable gaming chairs, RGB lighting, and our cafe menu 
                    with energy drinks and snacks to keep you powered up!
                </p>
            </div>

            <div class="card">
                <h3>Featured Gaming Stations</h3>
                <?php
                $featured = $conn->query("
                    SELECT gs.station_name, gs.station_number, dt.type_name, ps.spec_name, ps.graphics_card, gs.hourly_rate, gs.status
                    FROM gaming_stations gs
                    JOIN device_types dt ON gs.type_id = dt.type_id
                    LEFT JOIN pc_specs ps ON gs.spec_id = ps.spec_id
                    WHERE gs.status = 'Available'
                    ORDER BY gs.hourly_rate DESC
                    LIMIT 4
                ");
                ?>
                
                <div class="stations-grid">
                    <?php while ($station = $featured->fetch_assoc()): ?>
                        <div class="station-card">
                            <h4><?php echo htmlspecialchars($station['station_name']); ?></h4>
                            <p class="spec-detail"><strong>ID:</strong> <?php echo htmlspecialchars($station['station_number']); ?></p>
                            <p class="spec-detail"><strong>Type:</strong> <?php echo htmlspecialchars($station['type_name']); ?></p>
                            <?php if ($station['spec_name']): ?>
                                <p class="spec-detail"><strong>Spec:</strong> <?php echo htmlspecialchars($station['spec_name']); ?></p>
                                <p class="spec-detail"><strong>GPU:</strong> <?php echo htmlspecialchars($station['graphics_card']); ?></p>
                            <?php endif; ?>
                            <p class="spec-detail"><strong>Rate:</strong> $<?php echo number_format($station['hourly_rate'], 2); ?>/hour</p>
                            <p class="spec-detail">
                                <span class="badge badge-<?php echo strtolower($station['status']); ?>">
                                    <?php echo $station['status']; ?>
                                </span>
                            </p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="card">
                <h3>Recent Bookings</h3>
                <?php
                $recent = $conn->query("
                    SELECT b.booking_id, c.full_name, gs.station_name, ts.slot_date, ts.start_time, b.final_amount
                    FROM bookings b
                    JOIN customers c ON b.customer_id = c.customer_id
                    JOIN gaming_stations gs ON b.station_id = gs.station_id
                    JOIN time_slots ts ON b.slot_id = ts.slot_id
                    ORDER BY b.created_at DESC
                    LIMIT 5
                ");
                
                if ($recent->num_rows > 0): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Customer</th>
                                    <th>Station</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $recent->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo $row['booking_id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['station_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['slot_date'])); ?></td>
                                        <td><?php echo date('h:i A', strtotime($row['start_time'])); ?></td>
                                        <td>$<?php echo number_format($row['final_amount'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-muted);">No bookings yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <footer>
            <p>&copy; 2026 GameZone Elite Gaming Cafe | All Rights Reserved |</p>
        </footer>
    </div>
</body>
</html>

<?php $conn->close(); ?>