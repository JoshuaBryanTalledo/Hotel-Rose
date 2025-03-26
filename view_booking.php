<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: bookings.php');
    exit();
}

$booking_id = $_GET['id'];
// Update the query to include payment information
// Update the query to match your payment and bill tables
$query = "SELECT b.*, rt.name as room_type, rt.price, 
          p.status as payment_status, p.payment_method, p.created_at as payment_date,
          bills.subtotal, bills.tax, bills.total as bill_total, bills.status as bill_status
          FROM bookings b 
          JOIN rooms r ON b.room_number = r.room_number 
          JOIN room_types rt ON b.room_type_id = rt.id 
          LEFT JOIN payments p ON b.id = p.bill_id 
          LEFT JOIN bills ON b.id = bills.booking_id 
          WHERE b.id = ? AND b.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    header('Location: bookings.php');
    exit();
}

// Calculate total nights and amount
$check_in = new DateTime($booking['check_in']);
$check_out = new DateTime($booking['check_out']);
$nights = $check_in->diff($check_out)->days;
$total = $booking['price'] * $nights;
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Booking - Hotel Reservation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 mb-4">
                <a href="bookings.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Bookings
                </a>
            </div>
            <div class="col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-info-circle"></i> Booking Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <h5 class="text-primary"><i class="fas fa-bed"></i> Room Information</h5>
                                <p><strong>Room Number:</strong> <?php echo $booking['room_number']; ?></p>
                                <p><strong>Room Type:</strong> <?php echo $booking['room_type']; ?></p>
                                <p><strong>Price per Night:</strong> $<?php echo number_format($booking['price'], 2); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary"><i class="fas fa-calendar-alt"></i> Booking Information</h5>
                                <p><strong>Check-in Date:</strong> <?php echo $booking['check_in']; ?></p>
                                <p><strong>Check-out Date:</strong> <?php echo $booking['check_out']; ?></p>
                                <p><strong>Number of Nights:</strong> <?php echo $nights; ?></p>
                                <p><strong>Total Amount:</strong> $<?php echo number_format($total, 2); ?></p>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5 class="text-primary"><i class="fas fa-file-invoice-dollar"></i> Bill Details</h5>
                                <p><strong>Subtotal:</strong> $<?php echo number_format($booking['subtotal'], 2); ?></p>
                                <p><strong>Tax:</strong> $<?php echo number_format($booking['tax'], 2); ?></p>
                                <p><strong>Total Amount:</strong> $<?php echo number_format($booking['bill_total'], 2); ?></p>
                                <p><strong>Bill Status:</strong> 
                                    <span class="badge badge-<?php echo $booking['bill_status'] == 'paid' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($booking['bill_status']); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary"><i class="fas fa-credit-card"></i> Payment Details</h5>
                                <?php if ($booking['payment_status'] == 'completed'): ?>
                                    <p><strong>Payment Status:</strong> 
                                        <span class="badge badge-success">Completed</span>
                                    </p>
                                    <p><strong>Payment Method:</strong> <?php echo ucfirst($booking['payment_method']); ?></p>
                                    <p><strong>Payment Date:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['payment_date'])); ?></p>
                                <?php else: ?>
                                    <p><strong>Payment Status:</strong> 
                                        <span class="badge badge-<?php echo $booking['payment_status'] == 'pending' ? 'warning' : 'danger'; ?>">
                                            <?php echo ucfirst($booking['payment_status']); ?>
                                        </span>
                                    </p>
                                    <?php if ($booking['status'] == 'confirmed' && $booking['payment_status'] != 'failed'): ?>
                                        <a href="payment.php?booking_id=<?php echo $booking_id; ?>" class="btn btn-primary">
                                            Pay Now
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary"><i class="fas fa-info-circle"></i> Status</h5>
                                <p>
                                    <span class="badge badge-<?php echo $booking['status'] == 'confirmed' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function cancelBooking(bookingId) {
        if (confirm('Are you sure you want to cancel this booking?')) {
            $.ajax({
                url: 'cancel_booking.php',
                method: 'POST',
                data: { booking_id: bookingId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Booking cancelled successfully');
                        window.location.href = 'bookings.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while cancelling the booking');
                }
            });
        }
    }
    </script>
</body>
</html>