<?php
// Include your database connection code hereerror_reporting(E_ALL);
ini_set('display_errors', 'on');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "dir1";

$conn = new mysqli($host, $db_user, $db_password, $db_name);
// Include your database connection code here

// Check if the form is submitted for item deletion
if (isset($_POST['delete'])) {
    // Get the item ID to be deleted
    $item_id = $_POST['item_id'];
    
    // Debugging: Output received item ID
    echo "Received 'delete' request for item ID: $item_id";

    // SQL query to delete the item by its ID
    $delete_sql = "DELETE FROM Items WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        // Item deletion successful
        echo "Item deleted successfully";
        header("Location: my.php"); // Redirect back to your page
        exit();
    } else {
        // Item deletion failed
        echo "Error deleting item: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Handle cases where 'delete' was not set
    echo "Invalid request.";
}

?>
