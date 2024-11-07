<?php
// Include your database connection code here
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "dir1";

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted for item update
if (isset($_POST['update'])) {
    $item_id = $_POST['item_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $contact_details = $_POST['contact_details'];

    // SQL query to update the item
    $update_sql = "UPDATE Items SET name = ?, description = ?, price = ?, category = ?, contact_details = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdssi", $name, $description, $price, $category, $contact_details, $item_id);

    if ($stmt->execute()) {
        // Item updated successfully
        header("Location: my.php"); // Redirect back to your page
        exit();
    } else {
        // Item update failed
        echo "Error updating item: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Handle cases where 'update' was not set
    echo "Invalid request.";
}
?>
