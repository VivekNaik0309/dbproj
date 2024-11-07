<!DOCTYPE html>
<html>
<head>
    <title>Inbox</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #007BFF;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 30px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 20px;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 20px auto;
            padding: 20px;
        }

        .message-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .message-header {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .message-text {
            margin-bottom: 10px;
        }

        .reply-form {
            margin-top: 10px;
        }

        .reply-button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a class="navbar-brand" href="#">Inbox</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Inbox</h2>

        <?php
        // Check for user authentication and database connection
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); // Redirect to login page if not authenticated
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Database connection code
        $host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "dir1"; // Replace with your database name

        $conn = new mysqli($host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch inbox messages for the user
        $sql = "SELECT m.sender_id, m.message, m.sent_at, u.username FROM messages m
                INNER JOIN users u ON m.sender_id = u.user_id
                WHERE m.receiver_id = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo '<div class="message-box">';
                echo '<p class="message-header">From: ' . $row['username'] . '</p>';
                echo '<p class="message-text">' . $row['message'] . '</p>';
                echo '<p>Sent At: ' . $row['sent_at'] . '</p>';

                // Add a reply button
                echo '<form class="reply-form" method="post" action="send_message.php">';
                echo '<input type="hidden" name="receiver_id" value="' . $row['sender_id'] . '">';
                echo '<textarea name="message" placeholder="Type your reply here" rows="3"></textarea>';
                echo '<input type="submit" class="reply-button" value="Reply">';
                echo '</form>';

                echo '</div>';
            }

            $stmt->close();
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
