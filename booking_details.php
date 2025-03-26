<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$room_id = $_GET['room_id'];

// Fetch booking details
$query = "SELECT b.*, r.room_type, r.price_per_night as price, r.room_number, 
          rt.name as room_type_name
          FROM bookings b 
          JOIN rooms r ON b.room_number = r.room_number 
          JOIN room_types rt ON r.room_type_id = rt.id
          WHERE b.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Details - Rose Hotel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="booking-details-card">
            <h2>Your Booking Details</h2>
            <div class="row">
                <div class="col-md-8">
                    <div class="booking-info">
                        <h3>Reservation Information</h3>
                        <p><strong>Check-in:</strong> <?php echo date('F d, Y', strtotime($booking['check_in'])); ?></p>
                        <p><strong>Check-out:</strong> <?php echo date('F d, Y', strtotime($booking['check_out'])); ?></p>
                        <p><strong>Room Type:</strong> <?php echo $booking['room_type_name']; ?></p>
                        <p><strong>Room Number:</strong> <?php echo $booking['room_number']; ?></p>
                        <p><strong>Total Price:</strong> ₱<?php echo number_format($booking['price'], 2); ?>/night</p>
                        
                        <h3 class="mt-4">Guest Information</h3>
                        <p><strong>Name:</strong> <?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $booking['email']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $booking['phone']; ?></p>
                        
                        <div class="booking-actions mt-4">
                            <button class="btn btn-warning" onclick="modifyBooking(<?php echo $booking['id']; ?>)">
                                Modify Booking
                            </button>
                            <button class="btn btn-danger" onclick="cancelBooking(<?php echo $booking['id']; ?>)">
                                Cancel Booking
                            </button>
                            <button class="btn btn-success" onclick="downloadDetails(<?php echo $booking['id']; ?>)">
                                Download Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function modifyBooking(bookingId) {
            // Add modification logic
        }

        function cancelBooking(bookingId) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                $.ajax({
                    url: 'cancel_booking.php',
                    type: 'POST',
                    data: { booking_id: bookingId },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Booking cancelled successfully');
                            window.location.href = 'index.php';
                        }
                    }
                });
            }
        }

        function downloadDetails(bookingId) {
            window.location.href = `download_booking.php?booking_id=${bookingId}`;
        }
    </script>
</body>
</html>