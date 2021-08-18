<?php
$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli('msyql', 'laravel', 'laravel');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
