<?php   
require_once('db_config.php'); 
require_once('essential.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-form {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        .login-form h4 {
            margin-bottom: 20px;
            color: #333;
            padding: 10px;
            background-color: rgb(218, 245, 13);
            border-radius: 4px;
        }
        .login-form label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .login-form button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .output {
            margin-top: 20px;
            color: #333;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <form method="POST" action="">
            <h4>Admin Login</h4>
            <?php 
            // Example placeholder for error messages
            if (isset($_GET['error'])) {
                alert('danger', htmlspecialchars($_GET['error']));
            }
            ?>
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="Login" class="btn">Login</button>
        </form>

        <?php
        // Display form inputs
        if (isset($_POST['Login'])) {
            $frm_data = filteration($_POST);
            // Update query to match your column names
            $query = "SELECT * FROM admin WHERE ADMIN_NAME = ? AND ADMIN_PASS = ?";
            $values = array($frm_data['username'], $frm_data['password']);
            $res = select($query, $values, 'ss');

            if ($res->num_rows == 1) {
                $row=mysqli_fetch_assoc($res);
                session_start();
                $_SESSION['adminLogin'] = true;
                $_SESSION['admin_id'] = $row['ADMIN_ID'];
                redirect('dashboard.php');
            } else {
                alert('danger', 'Invalid Credentials');
            }
        }
        ?>
    </div>
</body>
</html>
