<!DOCTYPE html>
<html>
<head>
    <title>Registration Page - 2nd Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .container:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #007BFF;
        }

        .input-container {
            text-align: center;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .terms {
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register - 2nd Shop</h2>
        <form action="register1.php" method="post">
            <div class="input-container">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-container">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-container">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <input type="submit" name="register" value="Register">
        </form>
        
        <p class="terms">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>


<?php
if (isset($_POST['register'])) {
    // Database connection code here (You need to configure this)
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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Additional validation and data sanitization should be added here

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.')</script>";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the Users table
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username,$hashed_password,$email);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful. You can now log in.')</script>";
            // Redirect to a login page or any other destination
            // header("Location: login.php");
        } else {
            echo "<script>alert('Registration failed. Please try again.')</script>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

