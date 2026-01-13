<?php
/**
 * GameZone Elite Gaming Cafe
 * Database Connection File
 */

// Database configuration
define('DB_HOST', 'localhost:3307');  // Change to 3306 if needed
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gaming_cafe_db');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>