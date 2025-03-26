<?php
session_start();
header('Content-Type: application/json');

// Remove these debug lines
// var_dump($_POST);
// var_dump($_SESSION);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit();
}

include 'config/database.php';

// Debug: Print POST data and Session data
error_log("POST Data: " . print_r($_POST, true));
error_log("Session Data: " . print_r($_SESSION, true));

// Get guest information first
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : null;
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$phone = isset($_POST['phone']) ? $_POST['phone'] : null;
$address = isset($_POST['address']) ? $_POST['address'] : null;
$room_number = isset($_POST['room_number']) ? $_POST['room_number'] : null;
$room_type_id = isset($_POST['room_type']) ? $_POST['room_type'] : null;
$check_in = isset($_POST['check_in']) ? $_POST['check_in'] : null;
$check_out = isset($_POST['check_out']) ? $_POST['check_out'] : null;
$adults = isset($_POST['adults']) ? (int)$_POST['adults'] : 1;
$children = isset($_POST['children']) ? (int)$_POST['children'] : 0;

// Debug: Print processed variables
error_log("Processed Variables: " . print_r([
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
    'phone' => $phone,
    'room_number' => $room_number,
    'room_type_id' => $room_type_id,
    'check_in' => $check_in,
    'check_out' => $check_out
], true));

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || 
    empty($room_number) || empty($check_in) || empty($check_out) || empty($address)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
    exit;
}

try {
    // More strict availability check
    $check_availability = "SELECT * FROM bookings 
                         WHERE room_number = ? 
                         AND status = 'confirmed'
                         AND NOT (
                             check_out < ? OR 
                             check_in > ?
                         )";
    
    $stmt = $conn->prepare($check_availability);
    $stmt->bind_param("sss", 
        $room_number,
        $check_in,    // New booking check-in
        $check_out    // New booking check-out
    );
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This room is already booked for the selected dates']);
        exit;
    }

    // Continue with existing booking process...
    $conn->begin_transaction();

    // Insert booking with only the fields that exist in your table
    $query = "INSERT INTO bookings (user_id, room_number, room_type_id, check_in, check_out, status) 
              VALUES (?, ?, ?, ?, ?, 'confirmed')";

    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("isiss", 
        $_SESSION['user_id'],
        $room_number,
        $room_type_id,
        $check_in,
        $check_out
    );

    // Add error checking for bind_param
    if ($stmt->error) {
        throw new Exception("Bind failed: " . $stmt->error);
    }

    if ($stmt->execute()) {
        $booking_stmt = $stmt; // Save the first statement
        
        // Update room status to 'booked'
        $update_room = "UPDATE rooms SET status = 'booked' WHERE room_number = ?";
        $stmt = $conn->prepare($update_room);
        $stmt->bind_param("s", $room_number);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        // Close statements
        $booking_stmt->close();
        $stmt->close();

        echo json_encode([
            'status' => 'success',
            'redirect' => 'view_booking.php',
            'message' => 'Booking confirmed successfully!'
        ]);
    } else {
        throw new Exception($conn->error);
    }
} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if ($conn) {
        $conn->rollback();
    }
    error_log("Exception: " . $e->getMessage());
    echo json_encode([
        'status' => 'error', 
        'message' => 'Booking error: ' . $e->getMessage()  // Show actual error message
    ]);
}

// Only close connection, remove $stmt->close()
if ($conn) {
    $conn->close();
}