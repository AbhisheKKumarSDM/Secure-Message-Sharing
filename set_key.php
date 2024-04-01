<?php
include_once('config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $decryptionKey = trim($_POST["decryption_key"]);
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email address format. Please enter a valid email !";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $errorMessage = "Email address already exists. Please use a different email !";
        } else {
            // Insert new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, decryption_key) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $decryptionKey]);
            $successMessage = "User and decryption key added successfully!";
            unset($_POST);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set User</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">  
    <link href="css/style.css" rel="stylesheet">  
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Set User</h2>
                        <?php if (isset($errorMessage)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $errorMessage; ?></div>
                        <?php endif; ?>
                        <?php if (isset($successMessage)): ?>
                            <div class="alert alert-success" role="alert"><?php echo $successMessage; ?></div>
                        <?php endif; ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php if(isset($_POST["username"])){echo $_POST['username'];} ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php if(isset($_POST["email"])){echo $_POST['email'];} ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="decryption_key" class="form-label">Decryption Key:</label>
                                <div class="input-group">
                                    <input type="text" id="decryption_key" name="decryption_key" class="form-control" required readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="generateKey">Generate</button>
                                    <button type="button" class="btn btn-outline-secondary" id="copyKey">Copy</button>
                                </div>
                                <small class="form-text text-muted">Save this for future, it will not be shown again or change.</small>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <!-- Home button -->
                                <a href="index.html" class="btn btn-secondary ms-2">Home</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to generate a random 16-character key
            function generateKey() {
                var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var key = '';
                for (var i = 0; i < 16; i++) {
                    key += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return key;
            }

            // Function to copy key to clipboard
            function copyKey() {
                var keyInput = $('#decryption_key');
                keyInput.select();
                document.execCommand('copy');
            }

            // Initialize form
            function initializeForm() {
                var generatedKey = generateKey();
                $('#decryption_key').val(generatedKey);
            }

            // Event listener for generate key button
            $('#generateKey').click(function() {
                initializeForm();
            });

            // Event listener for copy key button
            $('#copyKey').click(function() {
                copyKey();
            });

        });
    </script>
</body>
</html>
