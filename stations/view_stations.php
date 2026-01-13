<?php
require_once '../includes/db_connect.php';

$query = "SELECT gs.*, dt.type_name, ps.spec_name, ps.processor, ps.graphics_card, ps.ram, ps.storage, ps.tier
          FROM gaming_stations gs
          JOIN device_types dt ON gs.type_id = dt.type_id
          LEFT JOIN pc_specs ps ON gs.spec_id = ps.spec_id
          ORDER BY gs.hourly_rate DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Stations - GameZone Elite</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎮 GAMEZONE ELITE 🎮</h1>
            <p>Gaming Stations & Equipment</p>
        </header>

        <nav>
            <ul>
                <li><a href="../index.php">🏠 Home</a></li>
                <li><a href="../customers/add_customer.php">➕ Add Customer</a></li>
                <li><a href="../customers/view_customers.php">👥 View Customers</a></li>
                <li><a href="view_stations.php">🖥️ Gaming Stations</a></li>
                <li><a href="../bookings/add_booking.php">📝 Book Now</a></li>
                <li><a href="../bookings/view_bookings.php">📋 View Bookings</a></li>
            </ul>
        </nav>

        <div class="content">
            <h2>All Gaming Stations (<?php echo $result->num_rows; ?>)</h2>
            
            <div class="stations-grid">
                <?php while ($station = $result->fetch_assoc()): ?>
                    <div class="station-card">
                        <h4><?php echo htmlspecialchars($station['station_name']); ?></h4>
                        <p class="spec-detail"><strong>Station ID:</strong> <?php echo htmlspecialchars($station['station_number']); ?></p>
                        <p class="spec-detail"><strong>Type:</strong> <?php echo htmlspecialchars($station['type_name']); ?></p>
                        
                        <?php if ($station['spec_name']): ?>
                            <p class="spec-detail"><strong>Tier:</strong> 
                                <span class="badge badge-<?php echo strtolower($station['tier']); ?>">
                                    <?php echo $station['tier']; ?>
                                </span>
                            </p>
                            <p class="spec-detail"><strong>Spec:</strong> <?php echo htmlspecialchars($station['spec_name']); ?></p>
                            <p class="spec-detail"><strong>CPU:</strong> <?php echo htmlspecialchars($station['processor']); ?></p>
                            <p class="spec-detail"><strong>GPU:</strong> <?php echo htmlspecialchars($station['graphics_card']); ?></p>
                            <p class="spec-detail"><strong>RAM:</strong> <?php echo htmlspecialchars($station['ram']); ?></p>
                            <p class="spec-detail"><strong>Storage:</strong> <?php echo htmlspecialchars($station['storage']); ?></p>
                        <?php endif; ?>
                        
                        <p class="spec-detail"><strong>Zone:</strong> <?php echo htmlspecialchars($station['location_zone']); ?></p>
                        <p class="spec-detail" style="font-size: 1.3em; margin-top: 10px;">
                            <strong style="color: var(--primary);">$<?php echo number_format($station['hourly_rate'], 2); ?>/hour</strong>
                        </p>
                        <p class="spec-detail">
                            <span class="badge badge-<?php echo strtolower($station['status']); ?>">
                                <?php echo $station['status']; ?>
                            </span>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <footer>
            <p>&copy; 2026 GameZone Elite Gaming Cafe | All Rights Reserved</p>
        </footer>
    </div>
</body>
</html>

<?php $conn->close(); ?>