-- GameZone Elite Gaming Cafe Database
-- Professional Gaming Center Management System

CREATE DATABASE IF NOT EXISTS gaming_cafe_db;
USE gaming_cafe_db;

-- Table 1: Device Types (PC, Console, VR)
CREATE TABLE device_types (
    type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- Table 2: PC Specifications
CREATE TABLE pc_specs (
    spec_id INT AUTO_INCREMENT PRIMARY KEY,
    spec_name VARCHAR(100) NOT NULL,
    processor VARCHAR(100) NOT NULL,
    graphics_card VARCHAR(100) NOT NULL,
    ram VARCHAR(50) NOT NULL,
    storage VARCHAR(50) NOT NULL,
    tier ENUM('Budget', 'Mid-Range', 'High-End', 'Ultimate') DEFAULT 'Mid-Range'
);

-- Table 3: Gaming Stations
CREATE TABLE gaming_stations (
    station_id INT AUTO_INCREMENT PRIMARY KEY,
    station_number VARCHAR(20) NOT NULL UNIQUE,
    station_name VARCHAR(100) NOT NULL,
    type_id INT NOT NULL,
    spec_id INT,
    hourly_rate DECIMAL(10,2) NOT NULL,
    location_zone VARCHAR(50),
    status ENUM('Available', 'Occupied', 'Maintenance') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_id) REFERENCES device_types(type_id),
    FOREIGN KEY (spec_id) REFERENCES pc_specs(spec_id)
);

-- Table 4: Customers
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    membership_tier ENUM('Basic', 'Premium', 'VIP', 'Elite') DEFAULT 'Basic',
    total_hours_played DECIMAL(10,2) DEFAULT 0,
    join_date DATE NOT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table 5: Time Slots
CREATE TABLE time_slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    slot_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    duration_hours DECIMAL(3,1) NOT NULL,
    is_peak_hour BOOLEAN DEFAULT FALSE
);

-- Table 6: Bookings
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    station_id INT NOT NULL,
    slot_id INT NOT NULL,
    booking_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    discount_applied DECIMAL(5,2) DEFAULT 0,
    final_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Pending', 'Paid', 'Cancelled') DEFAULT 'Pending',
    booking_status ENUM('Confirmed', 'Completed', 'Cancelled') DEFAULT 'Confirmed',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (station_id) REFERENCES gaming_stations(station_id) ON DELETE CASCADE,
    FOREIGN KEY (slot_id) REFERENCES time_slots(slot_id) ON DELETE CASCADE,
    UNIQUE KEY unique_booking (station_id, slot_id)
);

-- Table 7: Games Library
CREATE TABLE games_library (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    game_title VARCHAR(150) NOT NULL,
    genre VARCHAR(50),
    platform VARCHAR(50),
    rating VARCHAR(10),
    available BOOLEAN DEFAULT TRUE
);

-- Table 8: Snacks & Beverages
CREATE TABLE cafe_menu (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    category ENUM('Snacks', 'Beverages', 'Meals', 'Energy Drinks') NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    available BOOLEAN DEFAULT TRUE
);

-- Insert Device Types
INSERT INTO device_types (type_name, description) VALUES
('Gaming PC', 'High-performance desktop computers for PC gaming'),
('PlayStation 5', 'Sony PlayStation 5 console with latest games'),
('Xbox Series X', 'Microsoft Xbox Series X console'),
('Nintendo Switch', 'Portable Nintendo Switch console'),
('VR Station', 'Virtual Reality gaming with Oculus/HTC Vive'),
('Racing Simulator', 'Professional racing setup with steering wheel');

-- Insert PC Specifications
INSERT INTO pc_specs (spec_name, processor, graphics_card, ram, storage, tier) VALUES
('Budget Beast', 'Intel i5-12400F', 'NVIDIA GTX 1660 Super', '16GB DDR4', '512GB NVMe SSD', 'Budget'),
('Mid-Tier Master', 'Intel i5-13600K', 'NVIDIA RTX 3060 Ti', '16GB DDR4', '1TB NVMe SSD', 'Mid-Range'),
('High-End Hero', 'Intel i7-13700K', 'NVIDIA RTX 4070', '32GB DDR5', '1TB NVMe SSD', 'High-End'),
('Ultimate Warrior', 'Intel i9-13900K', 'NVIDIA RTX 4090', '64GB DDR5', '2TB NVMe SSD', 'Ultimate'),
('Streaming Station', 'AMD Ryzen 9 7950X', 'NVIDIA RTX 4080', '32GB DDR5', '2TB NVMe SSD', 'Ultimate');

-- Insert Gaming Stations
INSERT INTO gaming_stations (station_number, station_name, type_id, spec_id, hourly_rate, location_zone, status) VALUES
('PC-001', 'Dragon Station', 1, 4, 15.00, 'VIP Zone', 'Available'),
('PC-002', 'Phoenix Station', 1, 4, 15.00, 'VIP Zone', 'Available'),
('PC-003', 'Titan Station', 1, 3, 10.00, 'Premium Zone', 'Available'),
('PC-004', 'Warrior Station', 1, 3, 10.00, 'Premium Zone', 'Available'),
('PC-005', 'Knight Station', 1, 2, 7.00, 'Standard Zone', 'Available'),
('PC-006', 'Ranger Station', 1, 2, 7.00, 'Standard Zone', 'Available'),
('PC-007', 'Scout Station', 1, 1, 5.00, 'Basic Zone', 'Available'),
('PC-008', 'Rookie Station', 1, 1, 5.00, 'Basic Zone', 'Available'),
('PS5-001', 'PlayStation Alpha', 2, NULL, 8.00, 'Console Zone', 'Available'),
('PS5-002', 'PlayStation Beta', 2, NULL, 8.00, 'Console Zone', 'Available'),
('XBOX-001', 'Xbox Omega', 3, NULL, 8.00, 'Console Zone', 'Available'),
('XBOX-002', 'Xbox Sigma', 3, NULL, 8.00, 'Console Zone', 'Available'),
('VR-001', 'VR Portal', 5, NULL, 12.00, 'VR Zone', 'Available'),
('VR-002', 'VR Dimension', 5, NULL, 12.00, 'VR Zone', 'Available'),
('RACE-001', 'Speed Demon', 6, NULL, 10.00, 'Racing Zone', 'Available');

-- Insert Sample Customers
INSERT INTO customers (full_name, email, phone, membership_tier, total_hours_played, join_date) VALUES
('Alex Johnson', 'alex.j@email.com', '555-0101', 'Elite', 156.5, '2024-01-10'),
('Sarah Williams', 'sarah.w@email.com', '555-0202', 'VIP', 89.0, '2024-02-15'),
('Mike Chen', 'mike.c@email.com', '555-0303', 'Premium', 45.5, '2024-03-01'),
('Emma Davis', 'emma.d@email.com', '555-0404', 'Basic', 12.0, '2024-03-20'),
('Ryan Martinez', 'ryan.m@email.com', '555-0505', 'Premium', 67.5, '2024-02-28');

-- Insert Time Slots
INSERT INTO time_slots (slot_date, start_time, end_time, duration_hours, is_peak_hour) VALUES
('2026-01-15', '09:00:00', '11:00:00', 2.0, FALSE),
('2026-01-15', '11:00:00', '13:00:00', 2.0, FALSE),
('2026-01-15', '13:00:00', '15:00:00', 2.0, FALSE),
('2026-01-15', '15:00:00', '17:00:00', 2.0, TRUE),
('2026-01-15', '17:00:00', '19:00:00', 2.0, TRUE),
('2026-01-15', '19:00:00', '21:00:00', 2.0, TRUE),
('2026-01-15', '21:00:00', '23:00:00', 2.0, TRUE),
('2026-01-16', '09:00:00', '12:00:00', 3.0, FALSE),
('2026-01-16', '12:00:00', '15:00:00', 3.0, FALSE),
('2026-01-16', '15:00:00', '18:00:00', 3.0, TRUE);

-- Insert Sample Bookings
INSERT INTO bookings (customer_id, station_id, slot_id, booking_date, total_amount, discount_applied, final_amount, payment_status, booking_status) VALUES
(1, 1, 1, '2026-01-10', 30.00, 0.10, 27.00, 'Paid', 'Confirmed'),
(2, 3, 2, '2026-01-10', 20.00, 0.05, 19.00, 'Paid', 'Confirmed'),
(3, 5, 3, '2026-01-11', 14.00, 0.00, 14.00, 'Paid', 'Confirmed'),
(4, 9, 4, '2026-01-11', 16.00, 0.00, 16.00, 'Pending', 'Confirmed'),
(5, 13, 5, '2026-01-12', 24.00, 0.05, 22.80, 'Paid', 'Confirmed');

-- Insert Games Library
INSERT INTO games_library (game_title, genre, platform, rating) VALUES
('Cyberpunk 2077', 'RPG', 'PC', 'M'),
('Call of Duty: Modern Warfare III', 'FPS', 'PC/Console', 'M'),
('Elden Ring', 'Action RPG', 'PC/Console', 'M'),
('FIFA 24', 'Sports', 'PC/Console', 'E'),
('Valorant', 'FPS', 'PC', 'T'),
('League of Legends', 'MOBA', 'PC', 'T'),
('Fortnite', 'Battle Royale', 'PC/Console', 'T'),
('Gran Turismo 7', 'Racing', 'PlayStation', 'E');

-- Insert Cafe Menu
INSERT INTO cafe_menu (item_name, category, price) VALUES
('Red Bull Energy Drink', 'Energy Drinks', 3.50),
('Monster Energy', 'Energy Drinks', 3.50),
('Coca-Cola', 'Beverages', 2.00),
('Mountain Dew', 'Beverages', 2.00),
('Coffee', 'Beverages', 2.50),
('Doritos', 'Snacks', 3.00),
('Pringles', 'Snacks', 3.50),
('Pizza Slice', 'Meals', 5.00),
('Hot Dog', 'Meals', 4.00),
('Chicken Wings (6pc)', 'Meals', 7.00);

-- Create Views

-- View 1: Available Stations
CREATE VIEW available_stations_view AS
SELECT 
    gs.station_id,
    gs.station_number,
    gs.station_name,
    dt.type_name,
    ps.spec_name,
    ps.graphics_card,
    gs.hourly_rate,
    gs.location_zone,
    gs.status
FROM gaming_stations gs
JOIN device_types dt ON gs.type_id = dt.type_id
LEFT JOIN pc_specs ps ON gs.spec_id = ps.spec_id
WHERE gs.status = 'Available';

-- View 2: Customer Bookings Overview
CREATE VIEW customer_bookings_view AS
SELECT 
    b.booking_id,
    c.full_name,
    c.email,
    c.membership_tier,
    gs.station_name,
    gs.station_number,
    dt.type_name,
    ts.slot_date,
    ts.start_time,
    ts.end_time,
    ts.duration_hours,
    b.final_amount,
    b.payment_status,
    b.booking_status
FROM bookings b
JOIN customers c ON b.customer_id = c.customer_id
JOIN gaming_stations gs ON b.station_id = gs.station_id
JOIN device_types dt ON gs.type_id = dt.type_id
JOIN time_slots ts ON b.slot_id = ts.slot_id;

-- View 3: Station Revenue
CREATE VIEW station_revenue_view AS
SELECT 
    gs.station_id,
    gs.station_number,
    gs.station_name,
    dt.type_name,
    COUNT(b.booking_id) AS total_bookings,
    SUM(b.final_amount) AS total_revenue,
    AVG(b.final_amount) AS avg_booking_amount
FROM gaming_stations gs
JOIN device_types dt ON gs.type_id = dt.type_id
LEFT JOIN bookings b ON gs.station_id = b.station_id
GROUP BY gs.station_id;

-- View 4: PC Specs with Stations
CREATE VIEW pc_stations_view AS
SELECT 
    gs.station_id,
    gs.station_number,
    gs.station_name,
    ps.spec_name,
    ps.processor,
    ps.graphics_card,
    ps.ram,
    ps.storage,
    ps.tier,
    gs.hourly_rate,
    gs.location_zone,
    gs.status
FROM gaming_stations gs
JOIN pc_specs ps ON gs.spec_id = ps.spec_id
WHERE gs.type_id = 1;

SELECT 'GameZone Elite Database Created Successfully! 🎮' AS status;