<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.html");
    exit();
}

include('db_connection.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $company_id = $_GET['id'];

    $query = "DELETE FROM companies WHERE company_id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $company_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                header("Location: manageCompanies.php");
                exit();
            } else {
                echo "Error: No company found with the given ID.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Error: Company ID not provided.";
}

$conn->close();
?>
