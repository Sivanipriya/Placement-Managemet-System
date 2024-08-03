<?php
session_start();
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$connect = new mysqli("localhost", "root", "", "revised");

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
        // Check if token is valid and not expired
        $stmt = $connect->prepare("SELECT USN FROM password_resets WHERE token=? AND expiry >= NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $usn = $row['USN'];
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Update password in slogin table
            $stmt_update = $connect->prepare("UPDATE slogin SET Password=? WHERE USN=?");
            $stmt_update->bind_param("ss", $hashed_password, $usn);
            $stmt_update->execute();

            // Remove the token from password_resets table
            $stmt_delete = $connect->prepare("DELETE FROM password_resets WHERE token=?");
            $stmt_delete->bind_param("s", $token);
            $stmt_delete->execute();

            echo "Password reset successful. You can now <a href='login.php'>login</a>.";
        } else {
            echo "Invalid or expired token.";
        }
    } else {
        echo "Passwords do not match.";
    }
}
?>
