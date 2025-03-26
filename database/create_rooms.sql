-- Drop existing tables if they exist
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS room_types;

-- Create tables in correct order
CREATE TABLE IF NOT EXISTS room_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    price DECIMAL(10,2)
) ENGINE=InnoDB;

-- Insert or update room types
INSERT INTO room_types (id, name, price) VALUES 
(1, 'Deluxe Room', 100.00),
(2, 'Family Suite', 200.00),
(3, 'Executive Suite', 300.00)
ON DUPLICATE KEY UPDATE name=VALUES(name), price=VALUES(price);

-- Create rooms table
CREATE TABLE IF NOT EXISTS rooms (
    room_number VARCHAR(10) PRIMARY KEY,
    room_type_id INT,
    status VARCHAR(20) DEFAULT 'available'
) ENGINE=InnoDB;

-- Insert or update rooms
INSERT IGNORE INTO rooms (room_number, room_type_id) VALUES 
('101', 1), ('102', 1), ('103', 1), ('104', 1), ('105', 1),
('201', 2), ('202', 2), ('203', 2), ('204', 2), ('205', 2),
('301', 3), ('302', 3), ('303', 3), ('304', 3), ('305', 3);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10),
    room_type_id INT,
    check_in DATE,
    check_out DATE,
    status VARCHAR(20)
) ENGINE=InnoDB;