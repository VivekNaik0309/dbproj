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

// Check if the form is submitted
if (isset($_POST['update'])) {
    // Get the item ID to be updated
    $item_id = $_POST['item_id'];

    // Query to fetch the item's current details
    $select_sql = "SELECT * FROM Items WHERE id = ?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Item</title>
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

        .form {
            margin: 20px;
        }

        .form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        .form button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }

        .form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Item</h2>
        <form class="form" method="POST" action="process_update.php">
            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
            <label for="description">Description:</label>
            <input type="text" name="description" value="<?php echo $row['description']; ?>" required>
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo $row['price']; ?>" required>
            <label for="category">Category:</label>
            <select name="category">
                <option value="electronics" <?php if ($row['category'] === 'electronics') echo 'selected'; ?>>Electronics</option>
                <option value="clothing" <?php if ($row['category'] === 'clothing') echo 'selected'; ?>>Clothing</option>
                <option value="books" <?php if ($row['category'] === 'books') echo 'selected'; ?>>Books</option>
            </select><br>
            <label for="contact_details">Contact Details:</label>
            <input type="text" name="contact_details" value="<?php echo $row['contact_details']; ?>" required>
            <button type="submit" name="update">Update Item</button>
        </form>
    </div>
</body>
</html>
<?php
}
?>
