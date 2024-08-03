<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.html");
    exit();
}

include('db_connection.php');

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    
    // Prepare the DELETE statement
    $query = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: manageStudents.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}

$stmt->close();
$conn->close();
?>
