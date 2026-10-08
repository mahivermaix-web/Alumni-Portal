<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "alumni_portal_new";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Connection successful
// echo "Connected successfully!";
?>