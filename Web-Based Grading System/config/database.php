<?php
// Database connection settings
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'web-based grading system';

// Data Source Name (DSN) for MySQL connection
$dns = "mysql:host=$db_host;dbname=$db_name;charset=utf8";  // Added charset=utf8 for better compatibility with special characters

try {
    // Create a new PDO instance to connect to the database
    $db_conn = new PDO($dns, $db_user, $db_pass);
    // Set the PDO error mode to exception for better debugging
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    // If the connection fails, display an error message
    die("Database connection error: " . $error->getMessage());
}
?>