<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
        // ... existing nav items ...
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="view_booking.php">View Booking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        <?php else: ?>
            // ... login/register links ...
        <?php endif; ?>
    </ul>
</div>