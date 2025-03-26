<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get all bookings for the current user
$query = "SELECT b.*, rt.name as room_type, rt.price 
          FROM bookings b 
          JOIN rooms r ON b.room_number = r.room_number 
          JOIN room_types rt ON b.room_type_id = rt.id 
          WHERE b.user_id = ? 
          ORDER BY b.check_in DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Hotel Reservation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="fas fa-book"></i> My Bookings</h2>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room Number</th>
                                        <th>Room Type</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $booking['room_number']; ?></td>
                                        <td><?php echo $booking['room_type']; ?></td>
                                        <td><?php echo $booking['check_in']; ?></td>
                                        <td><?php echo $booking['check_out']; ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $booking['status'] == 'confirmed' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="view_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-info btn-sm">View</a>
                                            <?php if ($booking['status'] == 'confirmed'): ?>
                                            <button onclick="cancelBooking(<?php echo $booking['id']; ?>)" class="btn btn-danger btn-sm">Cancel</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
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
                        location.reload();
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