<?php
include 'config/database.php';

function getAvailableRooms($check_in, $check_out) {
    global $conn;
    
    $query = "SELECT r.* FROM rooms r 
              WHERE r.room_number NOT IN (
                  SELECT b.room_number FROM bookings b 
                  WHERE b.status != 'cancelled'
                  AND ((b.check_in BETWEEN ? AND ?) 
                  OR (b.check_out BETWEEN ? AND ?)
                  OR (b.check_in <= ? AND b.check_out >= ?))
              )";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", 
        $check_in, $check_out,
        $check_in, $check_out,
        $check_in, $check_out
    );
    $stmt->execute();
    return $stmt->get_result();
}