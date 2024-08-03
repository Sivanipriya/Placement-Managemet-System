<?php

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit("You must be logged in to view this page <a href='index.php'>Click here</a>");
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "revised");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
