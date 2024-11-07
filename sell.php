<!DOCTYPE html>
<html>
<head>
    <title>Sell Items</title>
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

        .navbar-items {
            display: flex;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 20px;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 20px auto;
            padding: 20px;
        }

        .form-container {
            text-align: center;
        }

        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 80%;
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

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .error-message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a class="navbar-brand" href="index.php">ThriftUtopia</a>
        <div class="navbar-items">
            <a href="all.php"><i class="fas fa-shopping-bag"></i> Shop</a>
            <a href="sell.php"><i class="fas fa-dollar-sign"></i> Sell</a>
            <a href="my.php"><i class="fas fa-suitcase"></i> My Items</a>
<a href="profile.php"><i class="fas fa-user"></i> Profile</a>

            <a href="watch.php"><i class="fas fa-heart"></i> Wishlist</a> <!-- Using an icon for Wishlist -->
            <a href="logout.php"> <i class="fas fa-door-open"></i> Logout</a>
        </div>
    </div>
    <div class="container">
        <h2>Sell an Item</h2>
        <div class="form-container">
            <form action="sell.php" method="post" enctype="multipart/form-data">
                <input type="text" name="item_name" placeholder="Item Name" required>
                <select name="category" required>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="books">Books</option>
                    <!-- Add more categories as needed -->
                </select>
                <input type="text" name ="contact_details" placeholder="Contact Details" required>
                <textarea name="item_description" placeholder="Item Description" rows="4" required></textarea>
                <input type="file" name="item_image" accept="image/*" required>
                <input type="text" name="price" placeholder="Price" required>
                <input type="submit" name="sell" value="Sell Item">
            </form>
        </div>
        <?php
        if (isset($successMessage)) {
            echo '<div class="success-message">' . $successMessage . '</div>';
        }
        if (isset($errorMessage)) {
            echo '<div class="error-message">' . $errorMessage . '</div>';
        }
        ?>
    </div>
</body>
</html>
<?php
session_start(); // Start the session

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Get the user's ID from the session
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php"); // Replace "login.php" with your actual login page
    exit(); // Stop further execution
}

if (isset($_POST['sell'])) {
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
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $contact_details = $_POST['contact_details'];
    $item_description = $_POST['item_description'];
    $price = $_POST['price'];

    // Handle file upload (item image)
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["item_image"]["name"]);

    // Check for file upload errors
    if ($_FILES["item_image"]["error"] !== UPLOAD_ERR_OK) {
        $errorMessage = 'File upload failed with error code: ' . $_FILES["item_image"]["error"];
    } else {
        // Move the uploaded file to the target location
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            // Insert item data into the database
            $sql = "INSERT INTO Items (name, description, price, image_url, category, seller_id, contact_details) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssdssis", $item_name, $item_description, $price, $target_file, $category, $user_id, $contact_details);

                if ($stmt->execute()) {
                    $successMessage = 'Item listed successfully.';
                } else {
                    $errorMessage = 'Item listing failed. Please try again.';
                }

                $stmt->close();
            } else {
                $errorMessage = 'Error preparing the SQL statement: ' . $conn->error;
            }
        } else {
            $errorMessage = 'Error moving the uploaded file to the target location.';
        }
    }

    $conn->close();
}
?>
