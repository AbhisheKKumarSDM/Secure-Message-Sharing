<?php
include_once('config/database.php');
include_once('helper/common.php');

// Check if form is submitted for sharing message
if (isset($_POST['share_message'])) {
    $message = $_POST['message'];
    $recipientEmail = $_POST['recipient_email'];

    // Check if recipient email exists in the database
    $recipient = checkEmailExists($recipientEmail);
    if ($recipient) {
        $decryptionKey = getDecryptionKey($recipientEmail);

        // Encryption Key (You should generate and securely store this key)
        $encryptionKey = $decryptionKey;

        // Encrypt the message using the recipient's decryption key
        $encryptedMessage = encryptMessage($message, $encryptionKey);

        // Save the encrypted message to the database
        $messageId = saveEncryptedMessage($encryptedMessage, $recipientEmail);
        $successMessage = "Message shared successfully !!";
        unset($_POST);
    } else {
        $errorMessage =  "Recipient email not found. Please add the recipient first.";
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
                        <h2 class="card-title text-center mb-4">Share Message</h2>
                        <?php if (isset($errorMessage)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $errorMessage; ?></div>
                        <?php endif; ?>
                        <?php if (isset($successMessage)): ?>
                            <div class="alert alert-success" role="alert"><?php echo $successMessage; ?></div>
                        <?php endif; ?>
                        <form method="post" onsubmit="return validateEncryptionKey()">
                            <div class="mb-3">
                                <label for="recipient_email" class="form-label">Recipient:</label>
                                <input type="email" id="recipient_email" name="recipient_email" class="form-control" value="<?php if(isset($_POST["recipient_email"])){echo $_POST['recipient_email'];} ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message:</label>
                                <textarea id="message" name="message" class="form-control" rows="4" required><?php if(isset($_POST["message"])){echo $_POST['message'];} ?></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="share_message" class="btn btn-primary">Share Message</button>
                                <!-- Home button -->
                                <a href="index.html" class="btn btn-secondary ms-2">Home</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (optional) -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        function validateEncryptionKey() {
            var encryptionKeyInput = $("#encryption_key");
            var encryptionKeyError = $("#encryptionKeyError");
            
            if (encryptionKeyInput.val().length !== 16) {
                encryptionKeyInput.addClass("is-invalid");
                encryptionKeyError.css("display", "block");
                return false;
            } else {
                encryptionKeyInput.removeClass("is-invalid");
                encryptionKeyError.css("display", "none");
                return true;
            }
        }
    </script>
</body>
</html>

