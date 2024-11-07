<!DOCTYPE html>
<html>
<head>
    <title>My Items</title>
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
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .update-button,
        .delete-button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            margin-right: 10px;
            cursor: pointer;
        }

        .update-button:hover,
        .delete-button:hover {
            background-color: #0056b3;
        }

        .item form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a class="navbar-brand" href="index.php">ThirftUtopia</a>
        <div class="navbar-items">
            <a href="all.php"><i class="fas fa-shopping-bag"></i> Shop</a>
            <a href="sell.php"><i class="fas fa-dollar-sign"></i> Sell</a>
            <a href="my.php"><i class="fas fa-suitcase"></i> My Items</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="watch.php"><i class="fas fa-heart"></i> Wishlist</a>
            <a href="logout.php"><i class="fas fa-door-open"></i> Logout</a>
        </div>
    </div>
    <div class="container">
        <h2>My Items</h2>

        <!-- Search and Filter Form -->
        <form method="GET">
            <input type="text" class="search-bar" name="search" placeholder="Search for items..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <select name="category" class="search-bar">
                <option value="all">All Categories</option>
                <option value="electronics">Electronics</option>
                <option value="clothing">Clothing</option>
                <option value="books">Books</option>
            </select>
            <input type="submit" class="update-button" value="Filter">
        </form>

        <!-- Display Items -->
        <div id="items">
<?php
            // Database connection code
            error_reporting(E_ALL);
ini_set('display_errors', 'on');


            $host = "localhost";
            $db_user = "root";
            $db_password = "";
            $db_name = "dir1";

            $conn = new mysqli($host, $db_user, $db_password, $db_name);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            session_start();

            // Check if the user is logged in
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                // Construct and execute SQL query for item listing
                $sql = "SELECT * FROM Items WHERE seller_id = ?";

                $category_filter = $search_filter = "";

                if (isset($_GET['category']) && $_GET['category'] != "all") {
                    $category_filter = $_GET['category'];
                    $sql .= " AND category = ?";
                }

                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search_filter = "%" . $_GET['search'] . "%";
                    $sql .= " AND (name LIKE ? OR description LIKE ?)";
                }

                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    if ($category_filter != "" && $search_filter != "") {
                        $stmt->bind_param("iss", $user_id, $category_filter, $search_filter);
                    } elseif ($category_filter != "") {
                        $stmt->bind_param("is", $user_id, $category_filter);
                    } elseif ($search_filter != "") {
                        $stmt->bind_param("ss", $search_filter, $search_filter);
                    } else {
                        $stmt->bind_param("i", $user_id);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();


                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="item">';
                            echo '<h3>' . $row['name'] . '</h3>';
                            echo '<p>Description: ' . $row['description'] . '</p>';
                            echo '<p>Price: $' . $row['price'] . '</p>';
                            echo '<p>Category: ' . $row['category'] . '</p>';
                            echo '<p>Contact Details: ' . $row['contact_details'] . '</p>';
                            echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" width="200">';
                            // Add form for updating each item
                            echo '<form method="POST" action="update_item.php" style="display: inline;">';
                            echo '<input type="hidden" name="item_id" value="' . $row['id'] . '">';
                            echo '<button type="submit" class="update-button" name="update">Update</button>';
                            echo '</form>';
                            // Add form for deleting each item
                            echo '<form method="POST" action="delete_item.php" style="display: inline;">';
                            echo '<input type="hidden" name="item_id" value="' . $row['id'] . '">';
                            echo '<button type="submit" class="delete-button" name="delete">Delete</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                    } else {
                        echo 'No items found.';
                    }

                    $stmt->close();
                } else {
                    echo 'Error preparing the SQL statement: ' . $conn->error;
                }

                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>
