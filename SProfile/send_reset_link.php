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
    $usn = $_POST['usn'];
    $check = $connect->query("SELECT * FROM slogin WHERE USN='$usn'");

    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $email = $row['Email'];
        $token = bin2hex(random_bytes(50));

        // Store the token in the database with an expiration date
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $stmt = $connect->prepare("INSERT INTO password_resets (USN, token, created_at, expiry) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("sss", $usn, $token, $expiry);
        $stmt->execute();

        // Send the email using PHPMailer
        $reset_link = "http://localhost/one/SProfile/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Please click the link below to reset your password:\n\n$reset_link";

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  
            $mail->SMTPAuth = true;
            $mail->Username = 'sivanipriyasenthilkumar@gmail.com';  
            $mail->Password = 'gmqo ynfx uawh fops';  
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'Mailer');
            $mail->addAddress($email);  

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            echo 'A password reset link has been sent to your email.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No account found with that USN.";
    }
}
?>
