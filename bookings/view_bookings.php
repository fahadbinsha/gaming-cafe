<?php
require_once '../includes/db_connect.php';

$query = "SELECT b.*, c.full_name, c.membership_tier, gs.station_name, gs.station_number, 
          dt.type_name, ts.slot_date, ts.start_time, ts.end_time, ts.duration_hours
          FROM bookings b
          JOIN customers c ON b.customer_id = c.customer_id
          JOIN gaming_stations gs ON b.station_id = gs.station_id
          JOIN device_types dt ON gs.type_id = dt.type_id
          JOIN time_slots ts ON b.slot_id = ts.slot_id
          ORDER BY b.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - GameZone Elite</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎮 GAMEZONE ELITE 🎮</h1>
            <p>All Bookings</p>
        </header>

        <nav>
            <ul>
                <li><a href="../index.php">🏠 Home</a></li>
                <li><a href="../customers/add_customer.php">➕ Add Customer</a></li>
                <li><a href="../customers/view_customers.php">👥 View Customers</a></li>
                <li><a href="../stations/view_stations.php">🖥️ Gaming Stations</a></li>
                <li><a href="add_booking.php">📝 Book Now</a></li>
                <li><a href="view_bookings.php">📋 View Bookings</a></li>
            </ul>
        </nav>

        <div class="content">
            <h2>All Bookings (<?php echo $result->num_rows; ?>)</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Tier</th>
                                <th>Station</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Total</th>
                                <th>Discount</th>
                                <th>Final</th>
                                <th>Payment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['booking_id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($row['membership_tier']); ?>">
                                            <?php echo $row['membership_tier']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['station_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['type_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['slot_date'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($row['start_time'])); ?></td>
                                    <td><?php echo $row['duration_hours']; ?>h</td>
                                    <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td><?php echo ($row['discount_applied'] * 100); ?>%</td>
                                    <td><strong style="color: var(--primary);">$<?php echo number_format($row['final_amount'], 2); ?></strong></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($row['payment_status']); ?>">
                                            <?php echo $row['payment_status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($row['booking_status']); ?>">
                                            <?php echo $row['booking_status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No bookings yet. <a href="add_booking.php" style="color: var(--primary);">Create your first booking!</a>
                </div>
            <?php endif; ?>
        </div>

        <footer>
            <p>&copy; 2026 GameZone Elite Gaming Cafe | All Rights Reserved</p>
        </footer>
    </div>
</body>
</html>

<?php $conn->close(); ?>