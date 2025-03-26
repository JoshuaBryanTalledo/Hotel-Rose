<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header('Location: bookings.php');
    exit();
}

$booking_id = $_GET['booking_id'];
$query = "SELECT b.*, rt.name as room_type, rt.price 
          FROM bookings b 
          JOIN room_types rt ON b.room_type_id = rt.id 
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

// Calculate total amount
$check_in = new DateTime($booking['check_in']);
$check_out = new DateTime($booking['check_out']);
$nights = $check_in->diff($check_out)->days;
$total = $booking['price'] * $nights;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment - Hotel Reservation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-credit-card"></i> Payment Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <h5 class="text-primary"><i class="fas fa-info-circle"></i> Booking Summary</h5>
                                <div class="p-3 bg-light rounded mb-3">
                                    <p><strong>Room Type:</strong> <?php echo $booking['room_type']; ?></p>
                                    <p><strong>Room Number:</strong> <?php echo $booking['room_number']; ?></p>
                                    <p><strong>Check-in:</strong> <?php echo $booking['check_in']; ?></p>
                                    <p><strong>Check-out:</strong> <?php echo $booking['check_out']; ?></p>
                                    <p><strong>Number of Nights:</strong> <?php echo $nights; ?></p>
                                    <p><strong>Total Amount:</strong> $<?php echo number_format($total, 2); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary"><i class="fas fa-money-check-alt"></i> Payment Form</h5>
                                <form id="paymentForm" class="p-3">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                                    <input type="hidden" name="amount" value="<?php echo $total; ?>">
                                    
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select name="payment_method" class="form-control" required>
                                            <option value="cash">Cash</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="debit_card">Debit Card</option>
                                            <option value="online">Online Payment</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            <i class="fas fa-check"></i> Process Payment
                                        </button>
                                        <a href="view_booking.php?id=<?php echo $booking_id; ?>" class="btn btn-secondary btn-block mt-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: 'process_payment.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Payment successful!');
                        window.location.href = 'view_booking.php?id=<?php echo $booking_id; ?>';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while processing the payment');
                }
            });
        });
    });
    </script>
</body>
</html>