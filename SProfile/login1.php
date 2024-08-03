
<?php
session_start();

// Check if the login form is submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $connect = mysqli_connect("localhost", "root", "", "revised") or die("Couldn't Connect");

    // Query to fetch user details
    $query = $connect->query("SELECT * FROM slogin WHERE USN='$username'");

    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $dbusername = $row['USN'];
        $dbpassword = $row['PASSWORD'];
        $Name = $row['Name'];
        $s_id = $row['s_id']; // Fetch s_id

        if ($username == $dbusername && $password == $dbpassword) {
            // Set session variables
            $_SESSION['username'] = $Name;
            $_SESSION['s_id'] = $s_id; // Store s_id in session

            header("Location: preferences.php");
            exit();
        } else {
            echo "Username and/or Password incorrect.";
        }
    } else {
        echo "User does not exist.";
    }

    mysqli_close($connect);
}
?>
