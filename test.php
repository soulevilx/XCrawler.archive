<?php
$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli('127.0.0.1', 'laravel', 'laravel', 'laravel');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
