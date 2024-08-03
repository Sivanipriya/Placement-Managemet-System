<?php
include 'index1.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usn = $_POST['USN'];
    
    // Fetch user details from the database using the USN
    $query = "SELECT Email FROM basicdetails WHERE USN = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $usn);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();

        if ($email) {
            // Generate a unique password reset token
            $token = bin2hex(random_bytes(50));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Save the token and expiry in the database
            $query = "INSERT INTO password_resets (USN, token, expiry) VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE token = VALUES(token), expiry = VALUES(expiry)";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("sss", $usn, $token, $expiry);
                $stmt->execute();
                $stmt->close();

                // Send reset email
                $resetLink = "https://localhost/reset_password.php?token=$token";
                $subject = "Password Reset Request";
                $message = "Hi,\n\nTo reset your password, please click the link below:\n$resetLink\n\nThis link will expire in 1 hour.";
                $headers = "From: no-reply@yourwebsite.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "A password reset link has been sent to your email.";
                } else {
                    echo "Failed to send email. Please try again.";
                }
            } else {
                echo "Database error. Please try again.";
            }
        } else {
            echo "USN not found.";
        }
    } else {
        echo "Database error. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
