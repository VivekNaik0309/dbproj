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

if (isset($_POST['update'])) {
    session_start();
    $user_id = $_SESSION['user_id'];

    // Get the values from the form
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the new password matches the confirmation
    if ($newPassword !== $confirmPassword) {
        echo "Password and Confirm Password do not match. Please try again.";
    } else {
        // Update username and email
        $updateUserSql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($updateUserSql);
        $stmt->bind_param("ssi", $newUsername, $newEmail, $user_id);

        if ($stmt->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating profile: " . $conn->error;
        }

        // Update password if a new one is provided
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePasswordSql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($updatePasswordSql);
            $stmt->bind_param("si", $hashedPassword, $user_id);

            if ($stmt->execute()) {
                echo "Password updated successfully.";
            } else {
                echo "Error updating password: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>