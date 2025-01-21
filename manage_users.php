<?php
require('db_config.php');
require('essential.php');
adminLogin(); // Ensure the user is logged in


// Handle Ban/Unban
if (isset($_GET['ban_id'])) {
    $ban_id = $_GET['ban_id'];
    $stmt = $con->prepare("UPDATE users SET status = 'banned' WHERE id = ?");
    $stmt->bind_param("i", $ban_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php"); // Redirect to refresh page
    exit();
}

if (isset($_GET['unban_id'])) {
    $unban_id = $_GET['unban_id'];
    $stmt = $con->prepare("UPDATE users SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $unban_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php"); // Redirect to refresh page
    exit();
}

// Handle Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php");
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* General styles */
        body {
            margin: 0;
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Header styling */
        header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            color: white;
        }

        .sidebar .nav-item {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar .nav-link {
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            border-bottom: 1px solid #495057;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .sidebar .nav-link.logout {
            background-color: #dc3545;
        }

        .sidebar .nav-link.logout:hover {
            background-color: #c82333;
        }

        /* Main content styles */
        .content {
            margin-left: 250px; 
            padding: 20px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Action button styles */
        .action-btn {
            padding: 8px 12px;
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

        .ban-btn {
            background-color: #ffc107;
        }

        .ban-btn:hover {
            background-color: #e0a800;
        }

        .unban-btn {
            background-color: #28a745;
        }

        .unban-btn:hover {
            background-color: #218838;
        }

        /* Table heading */
        .content h3 {
            margin-top: 0;
            font-size: 28px;
            color: #007bff;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }

            table th, table td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
    <script>
        function confirmDeletion(userId) {
            return confirm(`Are you sure you want to delete user with ID ${userId}?`);
        }
    </script>
</head>





<body>
<?php include('sidebar.php'); ?>
    <!-- Main content -->
    <div class="content">   
        <h3>Manage Users</h3>
        
        <!-- Users Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users from the database
                $query = "SELECT * FROM users";
                $result = $con->query($query);
                while ($row = $result->fetch_assoc()) {
                    $status = $row['status'] === 'banned' ? 'Banned' : 'Active';
                    $banAction = $row['status'] === 'banned' 
                                 ? "<a href='?unban_id=" . $row['id'] . "' class='action-btn unban-btn'>Unban</a>"
                                 : "<a href='?ban_id=" . $row['id'] . "' class='action-btn ban-btn'>Ban</a>";

                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($status) . "</td>
                            <td>
                                $banAction
                                <a href='?delete_id=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirmDeletion(" . htmlspecialchars($row['id']) . ");'>Delete</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
