<?php
include 'config/database.php';
header('Content-Type: application/json');

$booking_id = $_POST['booking_id'] ?? '';
$amount = $_POST['amount'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

if (empty($booking_id) || empty($amount) || empty($payment_method)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // First insert into bills table
    $subtotal = $amount;
    $tax = $subtotal * 0.12; // 12% tax
    $total = $subtotal + $tax;
    
    $bill_query = "INSERT INTO bills (booking_id, subtotal, tax, total, status) 
                   VALUES (?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($bill_query);
    $stmt->bind_param("iddd", $booking_id, $subtotal, $tax, $total);
    $stmt->execute();
    $bill_id = $conn->insert_id;

    // Then insert into payments table
    $payment_query = "INSERT INTO payments (bill_id, payment_method, amount, status) 
                     VALUES (?, ?, ?, 'completed')";
    $stmt = $conn->prepare($payment_query);
    $stmt->bind_param("isd", $bill_id, $payment_method, $total);
    $stmt->execute();

    // Update bill status
    $update_bill = "UPDATE bills SET status = 'paid' WHERE id = ?";
    $stmt = $conn->prepare($update_bill);
    $stmt->bind_param("i", $bill_id);
    $stmt->execute();

    // Update booking status
    $update_booking = "UPDATE bookings SET status = 'paid' WHERE id = ?";
    $stmt = $conn->prepare($update_booking);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['status' => 'success', 'message' => 'Payment processed successfully']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Payment processing failed']);
}

$conn->close();