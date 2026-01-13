<?php
require_once '../includes/db_connect.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $membership_tier = $_POST['membership_tier'];
    $join_date = date('Y-m-d');
    
    if (!empty($full_name)) {
        // Check if email exists
        $check = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email already registered! Please use a different email.";
        } else {
            $stmt = $conn->prepare("INSERT INTO customers (full_name, email, phone, membership_tier, join_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $full_name, $email, $phone, $membership_tier, $join_date);
            
            if ($stmt->execute()) {
                $success = "Customer registered successfully! Welcome to GameZone Elite!";
                $_POST = array();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $error = "Please enter customer name!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer - GameZone Elite</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎮 GAMEZONE ELITE 🎮</h1>
            <p>Register New Gamer</p>
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
            <h2>Register New Customer</h2>
            
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
                            <label>Full Name *</label>
                            <input type="text" name="full_name" required placeholder="Enter full name">
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address *</label>
                            <input type="email" name="email" required placeholder="gamer@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="555-0123">
                        </div>
                        
                        <div class="form-group">
                            <label>Membership Tier *</label>
                            <select name="membership_tier" required>
                                <option value="Basic">Basic - Standard Access</option>
                                <option value="Premium">Premium - 10% Discount</option>
                                <option value="VIP">VIP - 15% Discount + Priority</option>
                                <option value="Elite">Elite - 20% Discount + All Benefits</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn">Register Gamer</button>
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