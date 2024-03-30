<?php
// Encryption and Decryption Functions (Using OpenSSL)
function encryptMessage($message, $key) {
    return openssl_encrypt($message, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
}

function decryptMessage($encryptedMessage, $key) {
    return openssl_decrypt($encryptedMessage, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
}
// Function to check if the provided email exists in the database
function checkEmailExists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// Function to retrieve the encrypted message associated with the provided email
function getEncryptedMessage($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT encrypted_message FROM messages WHERE recipient_email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['encrypted_message'];
}
// Function to retrieve the decryption key associated with the recipient email
function getDecryptionKey($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT decryption_key FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['decryption_key'];
}
// Function to retrieve all messages for the provided email
function getAllMessages($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE recipient_email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Function to check if any message is unread 
function hasUnreadMessages($messages) {
    foreach ($messages as $message) {
        if ($message['read_status'] != 1) {
            return true;
        }
    }
    return false;
}
// Function to check if any message is undeleted
function hasUndeletedMessages($messages) {
    foreach ($messages as $message) {
        if ($message['delete_status'] != 1) {
            return true;
        }
    }
    return false;
}

// Function to save the encrypted message to the database
function saveEncryptedMessage($encryptedMessage, $recipientEmail) {
    global $pdo;
    $createdAt = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO messages (encrypted_message, recipient_email, created_at) VALUES (?, ?, ?)");
    $stmt->execute([$encryptedMessage, $recipientEmail, $createdAt]);
    return $pdo->lastInsertId();
}

// Update message status to mark it as read
function markMessageAsRead($email) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE messages SET read_status = 1 WHERE recipient_email = ?");
    $stmt->execute([$email]);
}
?>