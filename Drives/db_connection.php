<?php
$servername = "localhost"; // Change if necessary
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "pms_new"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// else{
//     echo "Hello";
// }
?>
