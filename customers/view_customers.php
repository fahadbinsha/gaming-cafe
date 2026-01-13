<?php
require_once '../includes/db_connect.php';

$query = "SELECT * FROM customers ORDER BY join_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers - GameZone Elite</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎮 GAMEZONE ELITE 🎮</h1>
            <p>Registered Gamers</p>
        </header>

        <nav>
            <ul>
                <li><a href="../index.php">🏠 Home</a></li>
                <li><a href="add_customer.php">➕ Add Customer</a></li>
                <li><a href="view_customers.php">👥 View Customers</a></li>
                <li><a href="../stations/view_stations.php">🖥️ Gaming Stations</a></li>
                <li><a href="../bookings/add_booking.php">📝 Book Now</a></li>
                <li><a href="../bookings/view_bookings.php">📋 View Bookings</a></li>
            </ul>
        </nav>

        <div class="content">
            <h2>All Registered Customers (<?php echo $result->num_rows; ?>)</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Membership</th>
                                <th>Hours Played</th>
                                <th>Join Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['customer_id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($row['membership_tier']); ?>">
                                            <?php echo $row['membership_tier']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($row['total_hours_played'], 1); ?>h</td>
                                    <td><?php echo date('M d, Y', strtotime($row['join_date'])); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($row['status']); ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No customers registered yet. <a href="add_customer.php" style="color: var(--primary);">Register your first customer!</a>
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