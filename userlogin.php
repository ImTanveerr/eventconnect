<?php   
require_once('db_config.php'); 
require_once('essentialuser.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
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
            background-color: #dae315;
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
    </style>
</head>
<body>
    <div class="login-form">
        <form method="POST" action="">
            <h4>User Login</h4>
            <?php 
            // Display any error messages
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
        // Handle form submission
        if (isset($_POST['Login'])) {
            $frm_data = filteration($_POST);
            $query = "SELECT * FROM users WHERE username = ? AND password = ?";
            $values = array($frm_data['username'], $frm_data['password']);
            $res = select($query, $values, 'ss');

            if ($res->num_rows == 1) {
                $row = mysqli_fetch_assoc($res);
                session_start();
                $_SESSION['userLogin'] = true;
                $_SESSION['user_id'] = $row['id'];
                redirect('user_dashboard.php');
            } else {
                alert('danger', 'Invalid Credentials');
            }
        }
        ?>
    </div>
</body>
</html>
