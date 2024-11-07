<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 20px auto;
            padding: 20px;
        }

        .profile-form {
            width: 60%;
            margin: 0 auto;
        }

        .profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        .profile-form button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .profile-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Profile</h2>
        <div class="profile-form">
            <?php
            // Database connection code
            $host = "localhost";
            $db_user = "root";
            $db_password = "";
            $db_name = "dir1";

            $conn = new mysqli($host, $db_user, $db_password, $db_name);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get the current user's details
            session_start();
            $user_id = $_SESSION['user_id'];

            $sql = "SELECT * FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Display user's details
            echo '<form method="POST" action="update_profile.php">';
            echo '<input type="hidden" name="user_id" value="' . $user['id'] . '">';
            echo 'Username: <input type="text" name="username" value="' . $user['username'] . '"><br>';
            echo 'Email: <input type="email" name="email" value="' . $user['email'] . '"><br>';
            echo 'New Password: <input type="password" name="new_password"><br>';
            echo 'Confirm Password: <input type="password" name="confirm_password"><br>';
            echo '<button type="submit" name="update">Update Profile</button>';
            echo '</form>';

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
