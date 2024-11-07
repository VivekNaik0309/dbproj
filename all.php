<!DOCTYPE html>
<html>
<head>
    <title>All Items</title>
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

        .search-bar {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .watchlist-button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
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

            <a href="watch.php"><i class="fas fa-heart"></i> Wishlist</a> <!-- Using an icon for Wishlist -->
            <a href="logout.php"> <i class="fas fa-door-open"></i> Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>All Items</h2>

        <!-- Search Bar and Category Filter Form -->
        <form method="GET">
            <input type="text" class="search-bar" name="search" placeholder="Search for items..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <select name="category">
                <option value="all" <?php echo (isset($_GET['category']) && $_GET['category'] == 'all') ? 'selected' : ''; ?>>All Categories</option>
                <option value="electronics" <?php echo (isset($_GET['category']) && $_GET['category'] == 'electronics') ? 'selected' : ''; ?>>Electronics</option>
                <option value="clothing" <?php echo (isset($_GET['category']) && $_GET['category'] == 'clothing') ? 'selected' : ''; ?>>Clothing</option>
                <option value="books" <?php echo (isset($_GET['category']) && $_GET['category'] == 'books') ? 'selected' : ''; ?>>Books</option>
            </select>
            <input type="submit" value="Filter">
        </form>

        <!-- Display Items -->
        <div id="items">
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

            // Initialize session
            session_start();

            // Check if the user is logged in
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                // Check if the "Add to Watchlist" button is clicked for a specific item
                if (isset($_GET['add_to_watchlist'])) {
                    // Get the item ID from the URL
                    $item_id = $_GET['add_to_watchlist'];

                    // Insert the item into the user's watchlist
                    $insert_sql = "INSERT INTO watchlist (user_id, item_id, added_on) VALUES (?, ?, NOW())";
                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("ii", $user_id, $item_id);

                    if ($stmt->execute()) {
                        echo '<p>Item added to your watchlist.</p>';
                    } else {
                        echo '<p>Error adding the item to your watchlist.</p>';
                    }

                    $stmt->close();
                }

                // Construct and execute SQL query for item listing
                $sql = "SELECT * FROM Items WHERE 1";

                if (isset($_GET['category']) && $_GET['category'] != "all") {
                    $category_filter = $_GET['category'];
                    $sql .= " AND category = ?";
                }

                if (isset($_GET['search']) && $_GET['search'] != "") {
                    $search_filter = "%" . $_GET['search'] . "%";
                    $sql .= " AND (name LIKE ? OR description LIKE ?)";
                }

                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    if (isset($category_filter)) {
                        $stmt->bind_param("s", $category_filter);
                    }

                    if (isset($search_filter)) {
                        $stmt->bind_param("ss", $search_filter, $search_filter);
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
                            echo '<button class="watchlist-button" onclick="addToWatchlist(' . $row['id'] . ')">Add to Watchlist</button>';
                                        echo '<a href="messaging.php?receiver_id=' . $row['seller_id'] . '" class="message-seller-button">Message Seller</a>';

                            echo '</div>';
                        }
                    } else {
                        echo 'No items found.';
                    }

                    $stmt->close();
                } else {
                    echo 'Error preparing the SQL statement: ' . $conn->error;
                }
            } else {
                echo '<p>Please log in to use the watchlist feature.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function addToWatchlist(itemId) {
            // Redirect to the same page with the item ID in the URL
            window.location.href = 'all.php?add_to_watchlist=' + itemId;
        }
    </script>
</body>
</html>
