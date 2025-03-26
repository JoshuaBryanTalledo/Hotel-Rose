-- Room Types Table
CREATE TABLE IF NOT EXISTS room_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    total_rooms INT NOT NULL DEFAULT 5,
    amenities TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Individual Rooms Table
CREATE TABLE IF NOT EXISTS rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type_id INT,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    floor INT,
    FOREIGN KEY (room_type_id) REFERENCES room_types(id)
);

-- Insert Room Types
INSERT INTO room_types (name, description, price, total_rooms, amenities) VALUES
('Deluxe Room', 'Luxurious room with city view and modern amenities.', 100.00, 5, 'King Bed, City View, WiFi, Mini Bar'),
('Family Suite', 'Spacious suite perfect for family stays.', 200.00, 5, 'Two Queen Beds, Living Area, Kitchen, WiFi'),
('Executive Suite', 'Premium suite with panoramic views and luxury amenities.', 300.00, 5, 'King Bed, Panoramic View, Jacuzzi, Living Room');

-- Insert Individual Rooms
INSERT INTO rooms (room_number, room_type_id, floor) VALUES
-- Deluxe Rooms
('101', 1, 1), ('102', 1, 1), ('103', 1, 1), ('104', 1, 1), ('105', 1, 1),
-- Family Suites
('201', 2, 2), ('202', 2, 2), ('203', 2, 2), ('204', 2, 2), ('205', 2, 2),
-- Executive Suites
('301', 3, 3), ('302', 3, 3), ('303', 3, 3), ('304', 3, 3), ('305', 3, 3);

-- Update bookings table to include room number
ALTER TABLE bookings ADD COLUMN room_number VARCHAR(10) AFTER room_id;
ALTER TABLE bookings ADD FOREIGN KEY (room_number) REFERENCES rooms(room_number);