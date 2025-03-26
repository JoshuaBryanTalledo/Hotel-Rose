<?php
session_start();
header('Content-Type: application/json');
include 'config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['booking_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$booking_id = $_POST['booking_id'];

try {
    $conn->begin_transaction();

    // Get room number before updating
    $query = "SELECT room_number FROM bookings WHERE id = ? AND user_id = ? AND status = 'confirmed'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if (!$booking) {
        throw new Exception('Booking not found or already cancelled');
    }

    // Update booking status
    $update_booking = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
    $stmt = $conn->prepare($update_booking);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // Update room status back to available
    $update_room = "UPDATE rooms SET status = 'available' WHERE room_number = ?";
    $stmt = $conn->prepare($update_room);
    $stmt->bind_param("s", $booking['room_number']);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Booking cancelled successfully']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();