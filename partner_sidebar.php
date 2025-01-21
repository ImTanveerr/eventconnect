<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Dashboard</title>
    
    <!-- Add Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        /* Header styling */
        header {
            background-color: #28a745;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            height: 100vh; /* Full height */
            position: fixed;
            top: 60px; /* Start below the header (adjust height of header) */
            left: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            border-bottom: 1px solid #444;
            transition: background-color 0.3s, color 0.3s;
            font-size: 16px;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            background-color: #28a745;
        }

        .sidebar .nav-link.logout {
            background-color: #dc3545;
            margin-top: 20px;
        }

        .sidebar .nav-link.logout:hover {
            background-color: #c82333;
        }

        /* Main content styles */
        .content {
            margin-left: 250px; /* Space for the sidebar */
            padding: 20px;
            margin-top: 60px; /* Ensure the content starts below the header */
        }

        .content h3 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .content p {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        Partner Dashboard
    </header>

    <div class="sidebar">
        <ul>
            <li>
                <a class="nav-link" href="partner_home.php">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li>
                <a class="nav-link" href="partner_services.php">
                    <i class="fas fa-cogs"></i> Manage Services
                </a>
            </li>
            <li>
                <a class="nav-link" href="partner_bookings.php">
                    <i class="fas fa-book"></i> View Bookings
                </a>
            </li>
            <li>
                <a class="nav-link" href="partner_profile.php">
                    <i class="fas fa-user"></i> Profile Settings
                </a>
            </li>
            <li>
                <a class="nav-link" href="addServices.php">
                    <i class="fas fa-plus-circle"></i> Add new Services
                </a>
            </li>
            <li>
                <a class="nav-link logout" href="partnerlogin.php?logout=true">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

</body>
</html>
