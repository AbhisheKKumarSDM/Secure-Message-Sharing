<?php

    // Database configuration
    $db_host = 'localhost';
    $db_name = 'chat_db';
    $db_user = 'root';
    $db_pass = '';

    // Connect to the database with check of error message
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        // Set PDO to throw exceptions on error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
?>