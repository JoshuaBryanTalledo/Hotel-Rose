<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel_reservation';

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $database";
$conn->query($sql);
$conn->select_db($database);

// Disable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Drop existing tables
$conn->query("DROP TABLE IF EXISTS bookings");
$conn->query("DROP TABLE IF EXISTS rooms");
$conn->query("DROP TABLE IF EXISTS room_types");

// Create room_types table
$conn->query("CREATE TABLE room_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    price DECIMAL(10,2)
)");

// Insert room types
$conn->query("INSERT INTO room_types (name, price) VALUES 
    ('Deluxe Room', 100.00),
    ('Family Suite', 200.00),
    ('Executive Suite', 300.00)");

// Create rooms table
$conn->query("CREATE TABLE rooms (
    room_number VARCHAR(10) PRIMARY KEY,
    room_type_id INT,
    status VARCHAR(20) DEFAULT 'available'
)");

// Insert rooms
$conn->query("INSERT INTO rooms (room_number, room_type_id) VALUES 
    ('101', 1), ('102', 1), ('103', 1), ('104', 1), ('105', 1),
    ('201', 2), ('202', 2), ('203', 2), ('204', 2), ('205', 2),
    ('301', 3), ('302', 3), ('303', 3), ('304', 3), ('305', 3)");

// Create bookings table
$conn->query("CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10),
    room_type_id INT,
    check_in DATE,
    check_out DATE,
    status VARCHAR(20)
)");

// Enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

echo "Database setup completed successfully!";
$conn->close();