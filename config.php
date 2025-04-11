<?php
/**
 * Database Configuration File
 * 
 * Contains all database connection settings and establishes the connection.
 * WARNING: In production, credentials should be stored securely outside web root.
 */

// Database configuration constants
define('DB_HOST', 'localhost');  // Database server hostname
define('DB_USER', 'root');       // Database username (avoid using root in production)
define('DB_PASS', '');           // Database password (empty for development only)
define('DB_NAME', 'School');     // Database name

/**
 * Establish database connection using MySQLi
 * 
 * Creates a new connection instance and verifies it was successful.
 * Terminates script execution if connection fails.
 */
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verify connection was successful
if ($conn->connect_error) {
    // In production, consider logging errors instead of displaying them
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper encoding
$conn->set_charset("utf8mb4");
?>
