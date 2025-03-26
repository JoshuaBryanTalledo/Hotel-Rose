<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Hotel Rose</title>
    <!-- Add these lines in your head section -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- Add these lines after your existing CSS links -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <header class="header-area">
        <div class="top-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="header-contact">
                            <span><i class="fas fa-phone"></i> +63 954 167 7756</span>
                            <span><i class="fas fa-envelope"></i> hotelroseinfo@gmail.com</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="header-social float-right">
                            <a href="https://www.facebook.com/kento.dessuu"><i class="fab fa-facebook"></i></a>
                            <a href="https://x.com/ChucasKent66445"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com/kentchucas/"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="main-header">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="index.php">
                        <div class="brand-text">
                            <h1>Rose Hotel</h1>
            
                        </div>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                        <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#rooms">Rooms</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact">Contact</a>
                            </li>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown">
                                        <i class="fas fa-user-circle"></i> My Account
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="profile.php">
                                            <i class="fas fa-user"></i> Profile
                                        </a>
                                        <a class="dropdown-item" href="view_booking.php">
                                            <i class="fas fa-calendar-check"></i> My Bookings
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="logout.php">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </div>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link btn-book" href="login.php">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn-book" href="register.php">Register</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
  
    <!-- Hero Section -->
    <section class="hero-area">
        <div class="hero-slider">
            <div class="single-hero-slide">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="hero-content">
                                <h1>Spend Your Holiday</h1>
                                <p>Experience the perfect blend of luxury and comfort in our carefully crafted spaces. Discover a home away from home with our exceptional service and stunning accommodations.</p>
                                <div class="hero-buttons">
                                    <a href="#about" class="btn btn-primary">Read More</a>
                                    <a href="#contact" class="btn btn-outline">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- After your hero section, add these sections -->
        </section>
    
        <!-- About Section -->
        <section id="about" class="section-padding">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <!-- Update the about image path -->
                        <div class="about-img">
                            <img src="img/about.jpg" alt="About Us" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-content">
                            <div class="section-title">
                                <h2>Welcome to Rose Hotel</h2>
                                <p>Your Home Away From Home</p>
                            </div>
                            <p class="about-text">Experience luxury redefined at Rose Hotel. Nestled in the heart of the city, our hotel combines elegant comfort with exceptional service to create unforgettable stays for our guests.</p>
                            <div class="about-features">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="feature-item">
                                            <i class="fas fa-bed"></i>
                                            <h4>Luxury Rooms</h4>
                                            <p>Comfortable and elegant accommodations</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="feature-item">
                                            <i class="fas fa-utensils"></i>
                                            <h4>Restaurant</h4>
                                            <p>Fine dining experience</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="feature-item">
                                            <i class="fas fa-spa"></i>
                                            <h4>Spa & Wellness</h4>
                                            <p>Relaxation and rejuvenation</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="feature-item">
                                            <i class="fas fa-swimming-pool"></i>
                                            <h4>Swimming Pool</h4>
                                            <p>Outdoor infinity pool</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Rooms Section -->
        <section id="rooms" class="section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center">
                            <h2>Our Rooms</h2>
                            <p>Discover Your Perfect Stay</p>
                        </div>
                    </div>
                </div>
                <div class="rooms-container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="room-card">
                                <div class="room-img">
                                    <img src="img/deluxe.jpeg" alt="Deluxe Room">
                                    <div class="price">
                                        <span class="amount">$100</span>
                                        <span class="per-night">/night</span>
                                    </div>
                                </div>
                                <div class="room-content">
                                    <h3>Deluxe Room</h3>
                                    <div class="room-features">
                                        <span><i class="fas fa-bed"></i> 1 King Bed</span>
                                        <span><i class="fas fa-user"></i> 2 Adults</span>
                                        <span><i class="fas fa-expand"></i> 30 m²</span>
                                    </div>
                                    <p>Luxurious room with city view and modern amenities.</p>
                                    <div class="booking-button">
                                        <button class="book-now btn btn-primary btn-block" data-room-id="1">Book Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="room-card">
                                <div class="room-img">
                                    <img src="img/family.jpeg" alt="Family Suite">
                                    <div class="price">
                                        <span class="amount">$200</span>
                                        <span class="per-night">/night</span>
                                    </div>
                                </div>
                                <div class="room-content">
                                    <h3>Family Suite</h3>
                                    <div class="room-features">
                                        <span><i class="fas fa-bed"></i> 2 Queen Beds</span>
                                        <span><i class="fas fa-user"></i> 4 Adults</span>
                                        <span><i class="fas fa-expand"></i> 45 m²</span>
                                    </div>
                                    <p>Spacious suite perfect for family stays.</p>
                                    <div class="booking-button">
                                        <button class="book-now btn btn-primary" data-room-id="2">Book Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="room-card">
                                <div class="room-img">
                                    <img src="img/executive.jpeg" alt="Executive Suite">
                                    <div class="price">
                                        <span class="amount">$300</span>
                                        <span class="per-night">/night</span>
                                    </div>
                                </div>
                                <div class="room-content">
                                    <h3>Executive Suite</h3>
                                    <div class="room-features">
                                        <span><i class="fas fa-bed"></i> 1 King Bed</span>
                                        <span><i class="fas fa-user"></i> 2 Adults</span>
                                        <span><i class="fas fa-expand"></i> 60 m²</span>
                                    </div>
                                    <p>Premium suite with panoramic views and luxury amenities.</p>
                                    <div class="booking-button">
                                        <button class="book-now btn btn-primary" data-room-id="3">Book Now</button>
                                        
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    
        <!-- Contact Section -->
        <section id="contact" class="section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center">
                            <h2>Contact Us</h2>
                            <p>Get in touch with us</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="contact-info">
                            <div class="contact-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <p>Purok 17 Poblacion, Nabunturan, Davao De Oro</p>
                            </div>
                            <div class="contact-info-item">
                                <i class="fas fa-phone"></i>
                                <p>+63 954 167 7756</p>
                            </div>
                            <div class="contact-info-item">
                                <i class="fas fa-envelope"></i>
                                <p>hotelroseinfo@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="contact-form-wrapper">
                            <form id="contactForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    
        <!-- Add this right before the scripts -->
                <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Book Your Stay</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="bookingForm">
                                    <input type="hidden" id="room_id" name="room_id">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" name="first_name" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" name="last_name" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Phone</label>
                                            <input type="tel" class="form-control" name="phone" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Check-in Date</label>
                                            <input type="date" class="form-control" name="check_in" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Check-out Date</label>
                                            <input type="date" class="form-control" name="check_out" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Adults</label>
                                            <select class="form-control" name="adults" required>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Children</label>
                                            <select class="form-control" name="children">
                                                <option value="0">0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Room Type</label>
                                            <select class="form-control" name="room_type" id="room_type" required>
                                                <option value="">Select Room Type</option>
                                                <option value="1">Deluxe Room</option>
                                                <option value="2">Family Suite</option>
                                                <option value="3">Executive Suite</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Room Number</label>
                                            <select class="form-control" name="room_number" id="room_number" required>
                                                <option value="">Select Room Number</option>
                                            </select>
                                            <small id="availabilityInfo" class="form-text"></small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" name="address" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Confirm Booking</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Update your scripts section -->
            <!-- Scripts -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                <script src="js/booking.js"></script>
                <script src="js/contact.js"></script>
            <script>
                // View room functionality
                document.querySelectorAll('.view-room').forEach(button => {
                    button.addEventListener('click', function() {
                        const roomId = this.getAttribute('data-room-id');
                        window.location.href = `view_room.php?room_id=${roomId}`;
                    });
                });
            
                // Smooth scrolling for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        document.querySelector(this.getAttribute('href')).scrollIntoView({
                            behavior: 'smooth'
                        });
                    });
                });
            </script>
            
            </body>
            </html>

