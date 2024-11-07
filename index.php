<!DOCTYPE html>
<html>
<head>
    <title>Trending Products</title>
    <!-- Include Bootstrap CSS and JS for the carousel to work -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .navbar {
            background-color: #007ddF;
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
            color: #aaa;
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

        .message {
            background-color: #007BFF;
            color: #aaa;
            text-align: center;
            padding: 30px;
        }

        .shop-name {
            font-size: 36px;
            font-weight: bold;
        }

        .tagline {
            font-size: 24px;
        }

        .notification {
    background-color: red;
    color: #fff;
    border-radius: 50%;
    padding: 5px 8px;
    font-size: 12px;
    position: relative;
    top: -10px;
    left: 5px;
}


        .description {
            font-size: 18px;
        }

.message {
    background: linear-gradient(135deg, #1E4B8C, #333333); /* Dark gradient background */
    color: #fff;
    text-align: center;
    padding: 30px;
    margin-top: 20px;
    border-radius: 10px;
    box-shadow: 0 0 50px rgba(0, 0.8, 0, 0.2);
}



    .shop-name {
    font-size: 36px;
    font-weight: bold;
    text-transform: none;
    letter-spacing: 2px;
    color: #ff5733; /* Your desired color code */
}

.animated-text {
    animation: colorChange 3s infinite alternate; /* Apply animation to the "ThriftUtopia" text */
}

@keyframes colorChange {
    30% {
        color: #ff5733; /* Initial color */
    }
    50% {
        color: #9f5165; /* Midway color */
    }
    100% {
        color: #ff5733; /* Final color (same as initial) */
    }
}


    .tagline {
        font-size: 24px;
        font-style: italic; /* Italics for the tagline */
        text-transform: capitalize; /* Capitalize the first letter of each word */
        letter-spacing: 1px; /* Slightly increased letter spacing */
    }

    .description {
        font-size: 18px;
        line-height: 1.5; /* Increased line height for readability */
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
        <a href="inbox.php"><i class="fas fa-inbox"></i> Inbox <span class="notification">5</span></a> <!-- Static number for notification -->
        <a href="watch.php"><i class="fas fa-heart"></i> Wishlist</a> <!-- Using an icon for Wishlist -->
        <a href="logout.php"><i class="fas fa-door-open"></i> Logout</a>
    </div>
</div>



    <!-- Homepage Message -->
    <div class="message">
<h1>Welcome to <span class="shop-name animated-text">ThriftUtopia</span></h1>
        <p class="tagline">"One Person's Discards, Another's Delight."</p>
        <p class="description">Discover hidden treasures in our thriving community of college students. List your used items and find unique deals.</p>
    </div>
    <!-- Rest of your content (carousel, trending products, etc.) goes here -->



    <div class="container">
        <h2 class="text-center">Trending Products</h2>
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

        // Retrieve trending products from the database (you may need to adjust the query)
        $sql = "SELECT * FROM Items ORDER BY RAND() LIMIT 3";
        $result = $conn->query($sql);

        // Display trending products in a carousel
        if ($result->num_rows > 0) {
            echo '<div id="trending-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">';
            $active = 'class="active"';
            for ($i = 0; $i < 3; $i++) {
                echo '<li data-target="#trending-carousel" data-slide-to="' . $i . '" ' . $active . '></li>';
                $active = '';
            }
            echo '</ol>
                    <div class="carousel-inner">';

            $active = 'active';
            while ($row = $result->fetch_assoc()) {
                echo '<div class="carousel-item ' . $active . '">
                        <img src="' . $row['image_url'] . '" alt="' . $row['name'] . '">
                        <div class="carousel-caption">
                            <h3>' . $row['name'] . '</h3>
                            <p>' . $row['description'] . '</p>
                            <p>Price: $' . $row['price'] . '</p>
                        </div>
                    </div>';
                $active = '';
            }

            echo '</div>
                <a class="carousel-control-prev" href="#trending-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#trending-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>';
        } else {
            echo '<p class="text-center">No trending products available.</p>';
        }

        $conn->close();
        ?>

    </div>
</body>
</html>
