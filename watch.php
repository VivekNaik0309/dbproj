<!DOCTYPE html>
<html>
<head>
    <title>Watchlist</title>
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

        .item {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }

        /* Add style for the "Remove from Watchlist" button */
        .remove-button {
            background-color: #FF0000; /* Change to your preferred color */
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            margin-right: 10px;
            cursor: pointer;
        }

        /* Add hover style for the button */
        .remove-button:hover {
            background-color: #FF3333; /* Change to your preferred hover color */
        }
    </style>
</head>
<body>
<div class="navbar">
        <a class="navbar-brand" href="index.php">2nd Shop</a>
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
        <h2>Watchlist</h2>

        <?php
        // Start the session
        session_start();

        // Include your database connection code here
        $host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "dir1";

        $conn = new mysqli($host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Handle item removal from Watchlist
            if (isset($_POST['remove'])) {
                $item_id_to_remove = $_POST['item_id'];

                // Delete the item from the Watchlist
                $delete_sql = "DELETE FROM Watchlist WHERE user_id = ? AND id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("ii", $user_id, $item_id_to_remove);

                if ($delete_stmt->execute()) {
                    // Item removed successfully
                } else {
                    echo "Failed to remove item from the watchlist.";
                }

                $delete_stmt->close();
            }

            // Retrieve items from the Watchlist associated with the user
            $sql = "SELECT Items.*, Watchlist.id as watchlist_id FROM Watchlist 
                    JOIN Items ON Watchlist.item_id = Items.id 
                    WHERE Watchlist.user_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="item">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>Description: ' . $row['description'] . '</p>';
                    echo '<p>Price: $' . $row['price'] . '</p>';
                    echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" width="200">';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="item_id" value="' . $row['watchlist_id'] . '">';
                    echo '<button type="submit" name="remove" class="remove-button">Remove from Watchlist</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo 'Your watchlist is empty.';
            }
        } else {
            echo '<p>Please log in to view your watchlist.</p>';
        }

        // Close the statement
        $stmt->close();

        $conn->close();
        ?>
    </div>
</body>
</html>
