<!DOCTYPE html>
<html>
<head>
    <title>Send Message</title>
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

        .message-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .message-form {
            margin-top: 10px;
        }

        .message-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .send-button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Send Message</h2>
        <div class="message-box">
            <form class="message-form" method="post" action="send_message.php">
                <?php
                // Fetch the receiver_id (seller_id) from the URL parameter
                if (isset($_GET['receiver_id'])) {
                    $receiver_id = $_GET['receiver_id'];
                    echo '<input type="hidden" name="receiver_id" value="' . $receiver_id . '">';
                } else {
                    echo 'Receiver ID not provided.';
                }
                ?>
                <textarea class="message-input" name="message" placeholder="Type your message here" rows="4"></textarea>
                <input type="submit" class="send-button" value="Send">
            </form>
        </div>
    </div>
</body>
</html>
