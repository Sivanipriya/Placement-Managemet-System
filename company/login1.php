<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username && $password) {
        $connect = mysqli_connect("localhost", "root", "", "revised");

        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $username = mysqli_real_escape_string($connect, $username);
        $password = mysqli_real_escape_string($connect, $password);

        $query = $connect->query("SELECT * FROM company WHERE Name='$username' AND Password='$password'");
        if ($query) {
            $numrows = $query->num_rows;

            if ($numrows != 0) {
                while ($row = $query->fetch_assoc()) {
                    $dbusername = $row['Name'];
                    $dbpassword = $row['Password'];
                }

                if ($username == $dbusername && $password == $dbpassword) {
                    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
                    $_SESSION['username'] = $username;
                } else {
                    $message = "Username and/or Password incorrect.";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    echo "<center>Redirecting you back to Login Page! If not Goto <a href='index.php'> Here </a></center>";
                    echo "<meta http-equiv='refresh' content='1;url=login.php'>";
                }
            } else {
                die("User not exist");
            }
        } else {
            echo "Error: " . $connect->error;
        }

        $connect->close();
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
?>
