<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminDashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, Admin</h2>
        <nav>
            <ul>
                <li><a href="manageStudents.php">Manage Students</a></li>
                <li><a href="manageCompanies.php">Manage Companies</a></li>
                <li><a href="adminLogout.php" class="logout-button">Logout</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
