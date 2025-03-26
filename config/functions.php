<?php
// Database connection
function dbConnect() {
    $host = 'localhost';
    $dbname = 'hotel_reservation';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check room availability
function checkRoomAvailability($room_id, $check_in, $check_out) {
    $pdo = dbConnect();
    $query = "SELECT COUNT(*) FROM bookings 
              WHERE room_id = :room_id 
              AND ((check_in BETWEEN :check_in AND :check_out) 
              OR (check_out BETWEEN :check_in AND :check_out))
              AND status != 'cancelled'";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':room_id' => $room_id,
        ':check_in' => $check_in,
        ':check_out' => $check_out
    ]);
    
    return $stmt->fetchColumn() == 0;
}

// Create new booking
function createBooking($guest_id, $room_id, $check_in, $check_out, $adults, $children) {
    if (!checkRoomAvailability($room_id, $check_in, $check_out)) {
        return ['success' => false, 'message' => 'Room not available for selected dates'];
    }

    $pdo = dbConnect();
    $query = "INSERT INTO bookings (guest_id, room_id, check_in, check_out, adults, children, status, created_at) 
              VALUES (:guest_id, :room_id, :check_in, :check_out, :adults, :children, 'pending', NOW())";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':guest_id' => $guest_id,
            ':room_id' => $room_id,
            ':check_in' => $check_in,
            ':check_out' => $check_out,
            ':adults' => $adults,
            ':children' => $children
        ]);
        
        $booking_id = $pdo->lastInsertId();
        createBill($booking_id);
        
        return ['success' => true, 'booking_id' => $booking_id];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Booking failed: ' . $e->getMessage()];
    }
}

// Calculate total bill
function calculateBill($booking_id) {
    $pdo = dbConnect();
    $query = "SELECT b.*, r.price_per_night 
              FROM bookings b 
              JOIN rooms r ON b.room_id = r.id 
              WHERE b.id = :booking_id";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([':booking_id' => $booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $check_in = new DateTime($booking['check_in']);
    $check_out = new DateTime($booking['check_out']);
    $nights = $check_in->diff($check_out)->days;
    
    $total = $booking['price_per_night'] * $nights;
    $tax = $total * 0.10; // 10% tax
    
    return [
        'subtotal' => $total,
        'tax' => $tax,
        'total' => $total + $tax
    ];
}

// Create bill
function createBill($booking_id) {
    $bill = calculateBill($booking_id);
    $pdo = dbConnect();
    
    $query = "INSERT INTO bills (booking_id, subtotal, tax, total, status, created_at) 
              VALUES (:booking_id, :subtotal, :tax, :total, 'pending', NOW())";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':booking_id' => $booking_id,
            ':subtotal' => $bill['subtotal'],
            ':tax' => $bill['tax'],
            ':total' => $bill['total']
        ]);
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

// Update booking status
function updateBookingStatus($booking_id, $status) {
    $pdo = dbConnect();
    $query = "UPDATE bookings SET status = :status WHERE id = :booking_id";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':booking_id' => $booking_id,
            ':status' => $status
        ]);
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

// Get available rooms
function getAvailableRooms($check_in, $check_out) {
    $pdo = dbConnect();
    $query = "SELECT r.* FROM rooms r 
              WHERE r.id NOT IN (
                  SELECT room_id FROM bookings 
                  WHERE ((check_in BETWEEN :check_in AND :check_out) 
                  OR (check_out BETWEEN :check_in AND :check_out))
                  AND status != 'cancelled'
              )";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':check_in' => $check_in,
        ':check_out' => $check_out
    ]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Process payment
function processPayment($bill_id, $payment_method, $amount) {
    $pdo = dbConnect();
    $query = "INSERT INTO payments (bill_id, payment_method, amount, status, created_at) 
              VALUES (:bill_id, :payment_method, :amount, 'completed', NOW())";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':bill_id' => $bill_id,
            ':payment_method' => $payment_method,
            ':amount' => $amount
        ]);
        
        // Update bill status
        $query = "UPDATE bills SET status = 'paid' WHERE id = :bill_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':bill_id' => $bill_id]);
        
        return true;
    } catch(PDOException $e) {
        return false;
    }
}
?>