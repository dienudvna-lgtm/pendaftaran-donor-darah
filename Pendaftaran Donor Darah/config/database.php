<?php
/**
 * PMI Connect Database Configuration
 * Database: pmi_connect
 * Type: MySQL/MariaDB
 */

if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'pmi_connect');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('SESSION_TIMEOUT')) {
    define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
}

// Create Connection
$conn = null;
try {
    mysqli_report(MYSQLI_REPORT_OFF);
    // Connect to server
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($conn && !$conn->connect_error) {
        // Auto-create database if not exists
        @$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        @$conn->select_db(DB_NAME);
        @$conn->set_charset(DB_CHARSET);

        // Auto-create users table if not exists
        $createUsersTable = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `profile_picture` VARCHAR(255) DEFAULT NULL,
            `bio` TEXT DEFAULT NULL,
            `is_active` BOOLEAN DEFAULT TRUE,
            INDEX idx_email (`email`),
            INDEX idx_username (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        @$conn->query($createUsersTable);

        // Auto-create login_logs table if not exists
        $createLogsTable = "CREATE TABLE IF NOT EXISTS `login_logs` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `login_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `ip_address` VARCHAR(45),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            INDEX idx_user_id (`user_id`),
            INDEX idx_login_time (`login_time`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        @$conn->query($createLogsTable);
    } else {
        $conn = null;
    }
} catch (Exception $e) {
    $conn = null;
}


