<?php
include_once('/config/database.php');
include_once('/helper/common.php');

if (isset($_POST['read_message'])) {
    $email = $_POST['email'];
    $decryptionKey = $_POST['decryption_key'];

    // Check if the provided email exists in the database
    $user = checkEmailExists($email);
    if ($user) {
        $savedDecryptionKey = getDecryptionKey($email);

        // Check if the provided decryption key matches the saved key
        if ($decryptionKey === $savedDecryptionKey) {
            // Retrieve all messages for the user
            $messages = getAllMessages($email);
            if ($messages) {
                $undeletedMessagesExist = hasUndeletedMessages($messages);
                if ($undeletedMessagesExist) {
                    $unreadMessagesExist = hasUnreadMessages($messages);
                    if ($unreadMessagesExist) {
                        $message = "<p>Decrypted Message:</p>";
                        $counter = 1;
                        foreach ($messages as $msg) {
                            // Check if the message is deleted or read
                            if ($msg['delete_status'] != 1 && $msg['read_status'] != 1) {
                                // Decrypt and display the message
                                $encryptedMessage = $msg['encrypted_message'];
                                $decryptedMessage = decryptMessage($encryptedMessage, $decryptionKey);
                                $message .= "<p class='msg_botm'>$counter. $decryptedMessage</p>";
                                $counter++;
                                // Mark the message as read
                                markMessageAsRead($email);
                            }
                        }
                    } else {
                        $message = "All messages have been read.";
                    }
                } else {
                    $message = "All messages have been deleted.";
                }
            } else {
                $message = "No messages found for the provided email.";
            }
        } else {
            $message = "Wrong Credentials !!";
        }
    } else {
        $message = "Wrong Credentials !!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Message Sharing</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">  
    <link href="css/style.css" rel="stylesheet">  
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Retrieve Message</h2>
                        <?php
                        if(isset($message)) {
                            echo '<div class="alert alert-success" role="alert">'; print_r($message) . '</div>';
                            echo '<form method="post" class="mt-4 text_right">';
                            echo '<button type="submit" name="remove_message" class="btn btn-danger">Back</button>';
                            echo '</form>';
                        } else {
                            echo '<form method="post">';
                            echo '<div class="mb-3">';
                            echo '<label for="email" class="form-label">Your Email:</label>';
                            echo '<input type="email" id="email" name="email" class="form-control" required>';
                            echo '</div>';
                            echo '<div class="mb-3">';
                            echo '<label for="decryption_key" class="form-label">Decryption Key:</label>';
                            echo '<input type="text" id="decryption_key" name="decryption_key" class="form-control" required>';
                            echo '</div>';
                            echo '<div class="text-center">';
                            echo '<button type="submit" name="read_message" class="btn btn-primary">Read Message</button>';
                            echo '<a href="index.html" class="btn btn-secondary ms-2">Home</a>';
                            echo '</div>';
                            echo '</form>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (optional) -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
