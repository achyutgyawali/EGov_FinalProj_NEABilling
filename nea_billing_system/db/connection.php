<?php
$servername = "localhost";
$username = "root"; // Default username for local server
$password = ""; // Default password for local server
$dbname = "nea_billing_system"; // Ensure this database exists

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
