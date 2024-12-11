<?php

$servername = "localhost";
$username = "root";
$password = "harry";
$dbname = "gp_karde"; 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    // If the connection fails, show an alert
    die("Connection failed: " . mysqli_connect_error());
} else {
    // If the connection is successful, show this message
    // echo '<script>alert("Connected to the database successfully!");</script>';
}

?>
