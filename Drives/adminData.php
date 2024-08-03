<?php
include('db_connection.php'); // Include your database connection file

// Admin credentials
$username = 'Pavithra';
$password = 'admin1'; // Replace with your desired password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the SQL statement
$query = "INSERT INTO admins (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "Admin user added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
