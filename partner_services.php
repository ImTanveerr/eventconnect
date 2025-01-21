<?php
require('db_config.php');
require('essential.php');

// Ensure the partner is logged in
partnerLogin();

// Fetch partner ID from session
$partner_id = $_SESSION['partner_id'];

// Handle deletion of a service
if (isset($_GET['delete'])) {
    $service_id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM manageservices WHERE id = ? AND organizer = ?");
    $stmt->bind_param("ii", $service_id, $partner_id);
    $stmt->execute();
    $stmt->close();
    header("Location: partner_services.php");
    exit();
}

// Fetch services added by the partner
$stmt = $con->prepare("SELECT id, service_name, description, cost, service_date, venue, status, service_category FROM manageservices WHERE organizer = ?");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$services = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .container h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn.edit {
            background-color: #28a745;
            color: #fff;
        }

        .btn.delete {
            background-color: #dc3545;
            color: #fff;
        }

        .btn.edit:hover {
            background-color: #218838;
        }

        .btn.delete:hover {
            background-color: #c82333;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-size: 16px;
        }
    </style>
</head>
<body>
<?php include('partner_sidebar.php'); ?>
    <div class="container">
        <h2>Manage Your Services</h2>

        <?php if (!empty($services)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $index => $service): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td><?php echo number_format($service['cost'], 2); ?></td>
                            <td><?php echo htmlspecialchars($service['service_date']); ?></td>
                            <td><?php echo htmlspecialchars($service['venue']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_category']); ?></td>
                            <td><?php echo htmlspecialchars($service['status']); ?></td>
                            <td>
                                <a href="editService.php?id=<?php echo $service['id']; ?>" class="btn edit">Edit</a>
                                <a href="?delete=<?php echo $service['id']; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No services found. Add your first service!</p>
        <?php endif; ?>
    </div>
</body>
</html>
