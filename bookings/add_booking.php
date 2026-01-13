<?php
require_once '../includes/db_connect.php';

$success = '';
$error = '';

// Get customers
$customers = $conn->query("SELECT customer_id, full_name, membership_tier FROM customers WHERE status = 'Active' ORDER BY full_name");

// Get available stations
$stations = $conn->query("SELECT gs.station_id, gs.station_name, gs.station_number, dt.type_name, gs.hourly_rate, gs.status 
                          FROM gaming_stations gs 
                          JOIN device_types dt ON gs.type_id = dt.type_id 
                          ORDER BY gs.station_name");

// Get time slots
$slots = $conn->query("SELECT * FROM time_slots ORDER BY slot_date, start_time");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $station_id = $_POST['station_id'];
    $slot_id = $_POST['slot_id'];
    $booking_date = date('Y-m-d');
    
    if (!empty($customer_id) && !empty($station_id) && !empty($slot_id)) {
        // Check if slot already booked
        $check = $conn->prepare("SELECT booking_id FROM bookings WHERE station_id = ? AND slot_id = ?");
        $check->bind_param("ii", $station_id, $slot_id);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $error = "This slot is already booked for this station!";
        } else {
            // Get pricing info
            $pricing = $conn->prepare("SELECT gs.hourly_rate, ts.duration_hours, c.membership_tier 
                                       FROM gaming_stations gs, time_slots ts, customers c 
                                       WHERE gs.station_id = ? AND ts.slot_id = ? AND c.customer_id = ?");
            $pricing->bind_param("iii", $station_id, $slot_id, $customer_id);
            $pricing->execute();
            $price_info = $pricing->get_result()->fetch_assoc();
            
            $total = $price_info['hourly_rate'] * $price_info['duration_hours'];
            
            // Apply membership discount
            $discount = 0;
            switch($price_info['membership_tier']) {
                case 'Premium': $discount = 0.10; break;
                case 'VIP': $discount = 0.15; break;
                case 'Elite': $discount = 0.20; break;
            }
            
            $final_amount = $total * (1 - $discount);
            
            // Insert booking
            $stmt = $conn->prepare("INSERT INTO bookings (customer_id, station_id, slot_id, booking_date, total_amount, discount_applied, final_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Paid')");
            $stmt->bind_param("iiisddd", $customer_id, $station_id, $slot_id, $booking_date, $total, $discount, $final_amount);
            
            if ($stmt->execute()) {
                $success = "Booking confirmed! Total: $" . number_format($final_amount, 2) . " (Discount: " . ($discount * 100) . "%)";
                $_POST = array();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
            $pricing->close();
        }
        $check->close();
    } else {
        $error = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Station - GameZone Elite</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎮 GAMEZONE ELITE 🎮</h1>
            <p>Book Your Gaming Station</p>
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
            <h2>Book Gaming Station</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <div class="card">
                    <form method="POST">
                        <div class="form-group">
                            <label>Select Customer *</label>
                            <select name="customer_id" required>
                                <option value="">Choose a customer...</option>
                                <?php $customers->data_seek(0); while ($customer = $customers->fetch_assoc()): ?>
                                    <option value="<?php echo $customer['customer_id']; ?>">
                                        <?php echo htmlspecialchars($customer['full_name']) . ' (' . $customer['membership_tier'] . ')'; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Select Gaming Station *</label>
                            <select name="station_id" required>
                                <option value="">Choose a station...</option>
                                <?php $stations->data_seek(0); while ($station = $stations->fetch_assoc()): ?>
                                    <option value="<?php echo $station['station_id']; ?>" <?php echo $station['status'] != 'Available' ? 'disabled' : ''; ?>>
                                        <?php echo htmlspecialchars($station['station_name']) . ' - ' . $station['type_name'] . ' ($' . $station['hourly_rate'] . '/hr) - ' . $station['status']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Select Time Slot *</label>
                            <select name="slot_id" required>
                                <option value="">Choose a time slot...</option>
                                <?php $slots->data_seek(0); while ($slot = $slots->fetch_assoc()): ?>
                                    <option value="<?php echo $slot['slot_id']; ?>">
                                        <?php 
                                        echo date('M d, Y', strtotime($slot['slot_date'])) . ' - ' . 
                                             date('h:i A', strtotime($slot['start_time'])) . ' to ' . 
                                             date('h:i A', strtotime($slot['end_time'])) . ' (' . 
                                             $slot['duration_hours'] . 'h)' . 
                                             ($slot['is_peak_hour'] ? ' - PEAK HOURS' : ''); 
                                        ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Membership Discounts:</strong><br>
                            • Premium: 10% off<br>
                            • VIP: 15% off<br>
                            • Elite: 20% off
                        </div>
                        
                        <button type="submit" class="btn">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>

        <footer>
            <p>&copy; 2026 GameZone Elite Gaming Cafe | All Rights Reserved</p>
        </footer>
    </div>
</body>
</html>

<?php $conn->close(); ?>