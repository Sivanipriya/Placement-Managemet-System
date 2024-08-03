<?php
// Database connection details
$servername = "localhost"; // Change this if your database is hosted elsewhere
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "revised"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$usn = $_POST['usn'];
$password = $_POST['password'];
$email = $_POST['email'];

// Insert data into the table
$sql = "INSERT INTO slogin (Name, USN, PASSWORD, Email) VALUES ('$name', '$usn', '$password', '$email')";

if ($conn->query($sql) === TRUE) {
    // Redirect to home.html after successful signup
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
