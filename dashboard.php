

<?php 
require('essential.php');
adminLogin(); // Ensure the user is logged in
// Logout logic
if (isset($_GET['logout'])) {
    logout(); // Call the logout function to destroy the session and redirect
}
?>
<body>    
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content -->
    <div class="content">
        <h3>Welcome to the Admin Panel</h3>
        <p>Use the tools on the sidebar to manage users, events, bookings, and more. Keep track of your application's activity with ease.</p>
    </div>
</body>
</html>
