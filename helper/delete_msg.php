<?php
include_once('../config/database.php');

// Delete Expired Messages from Database by change status using Cron
function deleteExpiredMessages() {
    global $pdo;
    $now = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("UPDATE messages SET delete_status = '1' WHERE expiry < ?");
    $stmt->execute([$now]);
}
?>