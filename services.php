<?php
require('db_config.php');
require('essentialuser.php');

// Ensure the user is logged in
userlogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $service_id = $_POST['service_id'];
        $user_id = $_SESSION['user_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Validate dates
        if (strtotime($start_date) >= strtotime($end_date)) {
            $message = "End date must be after the start date.";
        } else {
            // Calculate duration
            $duration = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24); // Convert seconds to days

            // Fetch service cost
            $stmt = $con->prepare("SELECT cost FROM ManageServices WHERE id = ?");
            $stmt->bind_param("i", $service_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($result) {
                $cost = $result['cost'];
                $total_cost = $duration * $cost;

                // Check if the service is already in the cart
                $stmt = $con->prepare("SELECT * FROM Cart WHERE user_id = ? AND service_id = ? AND status = 'in_cart'");
                $stmt->bind_param("ii", $user_id, $service_id);
                $stmt->execute();
                $existing_cart = $stmt->get_result();
                $stmt->close();

                if ($existing_cart->num_rows > 0) {
                    $message = "Service is already in your cart!";
                } else {
                    // Add service to the cart with calculated details
                    $stmt = $con->prepare("
                        INSERT INTO Cart (user_id, service_id, start_date, end_date, duration, total_cost, status) 
                        VALUES (?, ?, ?, ?, ?, ?, 'in_cart')");
                    $stmt->bind_param("iissid", $user_id, $service_id, $start_date, $end_date, $duration, $total_cost);
                    if ($stmt->execute()) {
                        $message = "Service added to cart successfully!";
                    } else {
                        $message = "Error adding service to cart.";
                    }
                    $stmt->close();
                }
            } else {
                $message = "Service not found!";
            }
        }
    }
}

// Fetch available services
$query = "SELECT * FROM ManageServices WHERE status = 'approved'";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-left: 250px; /* Space for sidebar */
            padding: 20px;
            margin-top: 60px; /* To align below the header */
        }

        h3 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        input[type="date"], button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include('user_sidebar.php'); ?>
    <div class="content">
        <h3>Available Services</h3>
        <?php if (isset($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Venue</th>
                    <th>Organizer ID</th>
                    <th>Cost (per day)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                        <td><?= htmlspecialchars($row['venue']) ?></td>
                        <td><?= htmlspecialchars($row['organizer']) ?></td>
                        <td>৳<?= htmlspecialchars($row['cost']) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="service_id" value="<?= $row['id'] ?>">
                                <input type="date" name="start_date" required>
                                <input type="date" name="end_date" required>
                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
