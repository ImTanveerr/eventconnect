<?php
require('db_config.php');
require('essential.php');
adminLogin(); // Ensure the user is logged in

// Handle Delete Booking
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $con->prepare("DELETE FROM Bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_booking.php");
    exit();
}

// Handle Update Booking Status (Confirm/Cancel)
if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $status = $_GET['update_status']; // 'confirmed' or 'cancelled'
    
    $stmt = $con->prepare("UPDATE Bookings SET status = ? WHERE booking_id = ?");
    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        /* Add your CSS styling here */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
        }

        /* Adjust the actions column width */
        td.actions, th.actions {
            width: 250px; /* Set the desired width for the actions column */
        }

        .action-btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
            font-size: 14px;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .approve-btn {
            background-color: #28a745;
        }
        .approve-btn:hover {
            background-color: #218838;
        }
        .reject-btn {
            background-color: #ffc107;
        }
        .reject-btn:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content -->
    <div class="content">
        <h3>Manage Bookings</h3>

        <!-- Bookings Table -->
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User Name</th>
                    <th>Service Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration (Days)</th>
                    <th>Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch bookings from the database
                $query = "
                    SELECT 
                        b.booking_id, 
                        u.username AS user_name, 
                        ms.service_name, 
                        b.start_date, 
                        b.end_date, 
                        DATEDIFF(b.end_date, b.start_date) + 1 AS duration, 
                        b.status 
                    FROM 
                        Bookings b
                    JOIN 
                        Users u ON b.user_id = u.id
                    JOIN 
                        manageservices ms ON b.service_id = ms.id
                ";
                $result = $con->query($query);
                while ($row = $result->fetch_assoc()) {
                    // Display status as 'Confirmed' or 'Cancelled'
                    $status_display = ucfirst($row['status']); // Convert 'confirmed' or 'cancelled' to capitalized form
                    echo "<tr>
                        <td>" . htmlspecialchars($row['booking_id']) . "</td>
                        <td>" . htmlspecialchars($row['user_name']) . "</td>
                        <td>" . htmlspecialchars($row['service_name']) . "</td>
                        <td>" . htmlspecialchars($row['start_date']) . "</td>
                        <td>" . htmlspecialchars($row['end_date']) . "</td>
                        <td>" . htmlspecialchars($row['duration']) . "</td>
                        <td>" . $status_display . "</td>
                        <td class='actions'>
                            <a href='?update_status=confirmed&id=" . $row['booking_id'] . "' class='action-btn approve-btn'>Confirm</a>
                            <a href='?update_status=cancelled&id=" . $row['booking_id'] . "' class='action-btn reject-btn'>Cancel</a>
                            <a href='?delete_id=" . $row['booking_id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
