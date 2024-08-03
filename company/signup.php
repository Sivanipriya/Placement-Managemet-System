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
$description = $_POST['description'];
$password = $_POST['password'];
$email = $_POST['email'];
$website_link = $_POST['website_link'];

// Sanitize and validate inputs
$name = $conn->real_escape_string($name);
$description = $conn->real_escape_string($description);
$password = $conn->real_escape_string($password);
$email = $conn->real_escape_string($email);
$website_link = $conn->real_escape_string($website_link);

// Insert data into the table
$sql = "INSERT INTO company (Name, Email,  Password,Description, Website_link) VALUES ('$name', '$email', '$password', '$description', '$website_link')";

if ($conn->query($sql) === TRUE) {
    // Redirect to index.php after successful signup
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
