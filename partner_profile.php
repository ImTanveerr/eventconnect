<?php
require('db_config.php');
require('essential.php');

// Ensure the partner is logged in
partnerLogin();

// Fetch partner information
$partner_id = $_SESSION['partner_id'];
$stmt = $con->prepare("SELECT partner_name, email, contact_phone, service_type, created_at FROM partners WHERE partner_id = ?");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$result = $stmt->get_result();
$partner = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #28a745;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .profile-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #28a745;
        }
        .profile-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #343a40;
        }
        .profile-header p {
            margin: 0;
            color: #6c757d;
            font-size: 16px;
        }
        .partner-info {
            margin: 20px 0;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .partner-info p {
            font-size: 18px;
            margin: 5px 0;
        }
        .partner-info strong {
            color: #495057;
        }
        .btn-group {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .btn {
            text-decoration: none;
            display: inline-block;
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #218838;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-top: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .profile-header img {
                margin-bottom: 10px;
            }
            .btn-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
<?php include('partner_sidebar.php'); ?>
    <!-- Main Content -->
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <img src="default_profile.png" alt="Partner Avatar"> <!-- Replace with dynamic image URL if available -->
            <div>
                <h1><?= htmlspecialchars($partner['partner_name']) ?></h1>
                <p>Partner since: <?= htmlspecialchars(date('F Y', strtotime($partner['created_at']))) ?></p>
            </div>
        </div>

        <!-- Partner Information -->
        <div class="partner-info">
            <p><strong>Email:</strong> <?= htmlspecialchars($partner['email']) ?></p>
            <p><strong>Contact Phone:</strong> <?= htmlspecialchars($partner['contact_phone']) ?></p>
            <p><strong>Service Type:</strong> <?= htmlspecialchars($partner['service_type']) ?></p>
        </div>

     
    </div>
</body>
</html>
