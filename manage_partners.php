<?php
require('db_config.php');
require('essential.php');
adminLogin(); // Ensure the user is logged in

// Handle Delete Partner
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $con->prepare("DELETE FROM partners WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_partners.php");
    exit();
}

// Handle Update Partner Status (Approve/Reject)
if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $partner_id = $_GET['id'];
    $status = $_GET['update_status']; // 'active' or 'inactive'
    
    $stmt = $con->prepare("UPDATE partners SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $partner_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_partners.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Partners</title>
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
        <h3>Manage Partners</h3>

        <!-- Partners Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch partners from the database
                $query = "SELECT * FROM partners";
                $result = $con->query($query);
                while ($row = $result->fetch_assoc()) {
                    // Display status as 'Active' or 'Inactive'
                    $status_display = ucfirst($row['status']); // Convert 'active' or 'inactive' to capitalized form
                    echo "<tr>
                        <td>" . htmlspecialchars($row['partner_id']) . "</td>
                        <td>" . htmlspecialchars($row['partner_name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['contact_phone']) . "</td>
                        <td>" . htmlspecialchars($row['service_type']) . "</td>
                        <td>" . $status_display . "</td>
                        <td class='actions'>
                            <a href='?update_status=active&id=" . $row['partner_id'] . "' class='action-btn approve-btn'>Activate</a>
                            <a href='?update_status=inactive&id=" . $row['partner_id'] . "' class='action-btn reject-btn'>Deactivate</a>
                            <a href='?delete_id=" . $row['partner_id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
