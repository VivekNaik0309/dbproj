<?php
// Check for user authentication and database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not authenticated
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle message submission
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "dir1"; // Replace with your database name

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    // Verify if the receiver_id exists in the items table
    $check_sql = "SELECT seller_id FROM items WHERE seller_id = ?";
    $stmt_check = $conn->prepare($check_sql);

    if ($stmt_check) {
        $stmt_check->bind_param("i", $receiver_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            echo "Receiver ID does not exist. Please check the item or user you're trying to message.";
            exit; // Exit script if receiver ID does not exist
        }
        $stmt_check->close();
    }

    // Insert the message into the 'messages' table
    $sql = "INSERT INTO messages (sender_id, receiver_id, message, sent_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    // Insert the message into the 'messages' table
$sql = "INSERT INTO messages (sender_id, receiver_id, message, sent_at) VALUES (?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    $stmt->execute();
    $stmt->close();
    echo "Message sent successfully.<br>";
    echo '<a href="all.php">Go Back</a>';
} else {
    echo "Error preparing the SQL statement: " . $conn->error . "<br>";
}


    $conn->close();
}
?>
