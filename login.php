<!DOCTYPE html>
<html>
<head>
    <title>Login to 2nd Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #62cff4  0%, #2c67f2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 69);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 300px;
            padding: 20px 0;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .container:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #007BFF;
            margin: 10px 0;
        }

        .input-container {
            text-align: center;
        }

        input[type="text"],
        input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #0056b3 0%, #003c7e 100%);
        }

        .terms {
            font-size: 12px;
            margin-top: 10px;
            color: #333;
        }

        .message {
            font-style: italic;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login to 2nd Shop</h2>
        <form action="login.php" method="post">
            <div class="input-container">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <input type="submit" name="login" value="Login">
        </form>
        
        <p class="message">"Unlock the door to endless shopping possibilities."</p>
    </div>
</body>
</html>

<?php
if (isset($_POST['login'])) {
    // Database connection code
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "dir1";

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Additional validation and data sanitization should be added here

    // Check if the user exists in the database
    $sql = "SELECT user_id, username, password FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $db_username, $db_password);
    $stmt->fetch();

    if ($stmt->num_rows == 1 && password_verify($password, $db_password)) {
        // Login successful
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $db_username;
        header("Location: index.php"); // Redirect to a dashboard or profile page
    } else {
        echo "<script>alert('Login failed. Please check your credentials.')</script>";
    }
    // After verifying the user's credentials
   if ($login_successful) {
    // Start a session
    session_start();

    // Set the user's ID in the session
    $_SESSION['user_id'] = $user_id; // Replace with the actual user ID
}


    $stmt->close();
    $conn->close();
}
?>
