<?php
include 'config/database.php';
header('Content-Type: application/json');

$room_type_id = $_POST['room_id'] ?? '';
$check_in = $_POST['check_in'] ?? '';
$check_out = $_POST['check_out'] ?? '';

if (empty($room_type_id) || empty($check_in) || empty($check_out)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

// Query to get all rooms of the selected type with their booking status
$query = "SELECT r.room_number, 
          CASE 
              WHEN b.id IS NOT NULL THEN 'booked'
              ELSE 'available'
          END as status
          FROM rooms r 
          LEFT JOIN bookings b ON r.room_number = b.room_number 
              AND b.status IN ('confirmed', 'paid')
              AND (
                  (? BETWEEN b.check_in AND b.check_out)
                  OR (? BETWEEN b.check_in AND b.check_out)
                  OR (b.check_in BETWEEN ? AND ?)
                  OR (b.check_out BETWEEN ? AND ?)
              )
          WHERE r.room_type_id = ?
          ORDER BY r.room_number";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssi", 
    $check_in, $check_out,
    $check_in, $check_out,
    $check_in, $check_out,
    $room_type_id
);

$stmt->execute();
$result = $stmt->get_result();
$rooms = [];

while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

echo json_encode([
    'status' => 'success',
    'rooms' => $rooms,
    'message' => count($rooms) . ' rooms found'
]);

$stmt->close();
$conn->close();