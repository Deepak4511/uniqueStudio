<?php

/**
 * UNIQUE STUDIO - Database Configuration
 * Connects to MySQL database using PDO
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');         // Change to your MySQL username
define('DB_PASS', '');             // Change to your MySQL password
define('DB_NAME', 'unique_studio');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_URL', 'http://localhost/uniqueStudio'); // Change for production
define('SITE_NAME', 'Unique Studio');
define('SITE_EMAIL', 'orders@uniquestudio.com');
define('SITE_PHONE', '+91 98765-43210');
define('WHATSAPP_NUMBER', '919876543210'); // Country code + number, no +

// Order Settings
define('GST_RATE', 18);           // GST percentage
define('DELIVERY_CHARGE', 100);   // Default delivery charge in INR
define('FREE_DELIVERY_ABOVE', 2000); // Free delivery above this amount
define('ORDER_PREFIX', 'UST');    // Order number prefix
define('CURRENCY_SYMBOL', '₹');   // Currency symbol

// File Upload Settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'ai', 'psd']);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

/**
 * Get database connection using PDO
 * @return PDO
 */
function getDB()
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log error in production, show friendly message
            error_log("Database connection failed: " . $e->getMessage());
            die(json_encode(['success' => false, 'message' => 'Database connection failed. Please try again.']));
        }
    }

    return $pdo;
}

/**
 * Get mysqli connection (for legacy code compatibility)
 * @return mysqli
 */
function getMysqli()
{
    static $conn = null;

    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            error_log("MySQLi connection failed: " . $conn->connect_error);
            die("Database connection failed. Please try again.");
        }

        $conn->set_charset(DB_CHARSET);
    }

    return $conn;
}
