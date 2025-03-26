$(document).ready(function() {
    // Handle room booking button
    $('.book-now').click(function(e) {
        e.preventDefault();
        const roomId = $(this).data('room-id');
        $('#bookingModal').modal('show');
        $('#room_id').val(roomId);
        // Set the room type based on the clicked room
        $('#room_type').val(roomId);
        // Trigger room availability check
        updateAvailabilityInfo(roomId);
    });

    // Handle room type change
    $('#room_type').change(function() {

        const roomId = $(this).val();
        // Remove the debug alert
        if (roomId) {
            $('#room_id').val(roomId);
            updateAvailabilityInfo(roomId);
        } else {
            $('#room_number').html('<option value="">Select Room Number</option>');
            $('#availabilityInfo').html('');
        }
    });

    // Add this after document.ready
    // Set min date for check-in and check-out
    const today = new Date().toISOString().split('T')[0];
    $('input[name="check_in"]').attr('min', today);
    $('input[name="check_out"]').attr('min', today);

    // Update check-out min date when check-in is selected
    $('input[name="check_in"]').on('change', function() {
        $('input[name="check_out"]').attr('min', $(this).val());
        if ($('input[name="check_out"]').val() < $(this).val()) {
            $('input[name="check_out"]').val('');
        }
        const roomId = $('#room_type').val();
        if (roomId) {
            updateAvailabilityInfo(roomId);
        }
    });

    // Handle check-out date change
    $('input[name="check_out"]').on('change', function() {
        const roomId = $('#room_type').val();
        if (roomId) {
            updateAvailabilityInfo(roomId);
        }
    });

    function updateAvailabilityInfo(roomId) {
        const checkIn = document.querySelector('input[name="check_in"]').value;
        const checkOut = document.querySelector('input[name="check_out"]').value;
        
        if (checkIn && checkOut) {
            $.ajax({
                url: 'check_booking.php',
                type: 'POST',
                data: {
                    room_id: roomId,
                    check_in: checkIn,
                    check_out: checkOut
                },
                success: function(response) {
                    const roomSelect = document.getElementById('room_number');
                    const availabilityInfo = document.getElementById('availabilityInfo');
                    
                    roomSelect.innerHTML = '<option value="">Select Room Number</option>';
                    
                    if (response.rooms && Array.isArray(response.rooms)) {
                        let availableCount = 0;
                        
                        response.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.room_number;
                            if (room.status === 'available') {
                                option.textContent = `Room ${room.room_number} - Available`;
                                option.className = 'text-success';
                                availableCount++;
                            } else {
                                option.textContent = `Room ${room.room_number} - Booked`;
                                option.className = 'text-danger';
                                option.disabled = true;
                            }
                            roomSelect.appendChild(option);
                        });
                        
                        if (availableCount > 0) {
                            availabilityInfo.innerHTML = `${availableCount} rooms available`;
                            availabilityInfo.className = 'text-success';
                        } else {
                            availabilityInfo.innerHTML = 'No rooms available for selected dates';
                            availabilityInfo.className = 'text-danger';
                        }
                    }
                }
            });
        }
    }

    // Add event listeners to check-in/check-out dates
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('change', function() {
            const roomId = $('#room_type').val();
            if (roomId) {
                updateAvailabilityInfo(roomId);
            }
        });
    });
    // Handle booking form submission
    function checkRoomAvailability(roomId) {
        const checkIn = document.querySelector('input[name="check_in"]').value;
        const checkOut = document.querySelector('input[name="check_out"]').value;
        const roomNumber = document.querySelector('select[name="room_number"]').value;
        
        // Check if all required fields are filled
        const requiredFields = [
            'first_name', 'last_name', 'email', 'phone',
            'check_in', 'check_out', 'adults', 'room_type',
            'room_number', 'address'
        ];
        
        const missingFields = requiredFields.filter(field => {
            const element = document.querySelector(`[name="${field}"]`);
            return !element.value.trim();
        });

        if (missingFields.length > 0) {
            alert('Please fill in all required fields');
            return Promise.resolve(false);
        }

        if (!checkIn || !checkOut) {
            alert('Please select check-in and check-out dates');
            return Promise.resolve(false);
        }

        return $.ajax({
            url: 'check_booking.php',
            type: 'POST',
            data: {
                room_id: roomId,
                check_in: checkIn,
                check_out: checkOut,
                room_number: roomNumber
            }
        }).then(function(response) {
            if (response.status === 'unavailable') {
                alert(response.message);
                return false;
            }
            return true;
        });
    }

    // Modify the form submission handler
    $('#bookingForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'process_booking.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.status === 'success') {
                    alert('Booking confirmed successfully!');
                    // Clear all form fields
                    $('#bookingForm')[0].reset();
                    // Reset any select dropdowns to default
                    $('#room_type').val('').trigger('change');
                    $('#room_number').val('').trigger('change');
                    // Clear any date pickers
                    $('#check_in').val('');
                    $('#check_out').val('');
                    // Redirect to view booking page
                    window.location.href = response.redirect;
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });
    
    function submitBooking() {
        // Your existing booking submission code
        const formData = $('#bookingForm').serialize();
        $.ajax({
            url: 'process_booking.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    alert('Booking confirmed successfully!');
                    $('#bookingModal').modal('hide');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const bookButtons = document.querySelectorAll('.book-now');
    if (bookButtons.length > 0) {
        bookButtons.forEach(button => {
            button.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                if (roomId) {
                    document.getElementById('room_id').value = roomId;
                    $('#bookingModal').modal('show');
                }
            });
        });
    }
});


function updateAvailableRooms() {
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;
    
    if (checkIn && checkOut) {
        fetch('get_available_rooms.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `check_in=${checkIn}&check_out=${checkOut}`
        })
        .then(response => response.json())
        .then(rooms => {
            const roomSelect = document.getElementById('room_number');
            roomSelect.innerHTML = '';
            
            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.room_number;
                option.textContent = `Room ${room.room_number} - ${room.room_type}`;
                roomSelect.appendChild(option);
            });
        });
    }
}

// Add event listeners to date inputs
document.getElementById('check_in').addEventListener('change', updateAvailableRooms);
document.getElementById('check_out').addEventListener('change', updateAvailableRooms);