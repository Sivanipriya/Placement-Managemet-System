<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("location: index.php");
    die("You must be logged in to view this page <a href='index.php'>Click here</a>");
}

$username = $_SESSION["username"];

// Database connection
$connect = mysqli_connect("localhost", "root", "", "revised");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the student ID based on the username
$s_id_query = "SELECT s_id FROM slogin WHERE Name = ?";
if ($stmt = mysqli_prepare($connect, $s_id_query)) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $s_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$s_id) {
        die("Student ID not found for the given username.");
    }
} else {
    die("Failed to prepare the SQL statement for fetching student ID: " . mysqli_error($connect));
}

// Prepare SQL queries for each status
$queries = [
    'Applied' => "
        SELECT 
            c.Name AS CompanyName, 
            j.Name AS JobName
        FROM 
            application a
        JOIN 
            company c ON a.Company_id = c.Company_id
        JOIN 
            job j ON a.Job_id = j.Job_id
        WHERE 
            a.s_id = ? AND a.Appln_status = -1
    ",
    'Rejected' => "
        SELECT 
            c.Name AS CompanyName, 
            j.Name AS JobName
        FROM 
            application a
        JOIN 
            company c ON a.Company_id = c.Company_id
        JOIN 
            job j ON a.Job_id = j.Job_id
        WHERE 
            a.s_id = ? AND a.Appln_status = 0
    ",
    'Selected' => "
        SELECT 
            c.Name AS CompanyName, 
            j.Name AS JobName
        FROM 
            application a
        JOIN 
            company c ON a.Company_id = c.Company_id
        JOIN 
            job j ON a.Job_id = j.Job_id
        WHERE 
            a.s_id = ? AND a.Appln_status = 1
    "
];

$data = [];

foreach ($queries as $status => $query) {
    if ($stmt = mysqli_prepare($connect, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $s_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $company_name, $job_name);
        
        $results = [];
        while (mysqli_stmt_fetch($stmt)) {
            $results[] = [
                'CompanyName' => htmlspecialchars($company_name),
                'JobName' => htmlspecialchars($job_name)
            ];
        }
        $data[$status] = $results;
        mysqli_stmt_close($stmt);
    } else {
        die("Failed to prepare the SQL statement for $status applications.");
    }
}

mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Status</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/icon">
    <link rel="icon" href="favicon.ico" type="image/icon">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/templatemo-style.css" rel="stylesheet">
</head>
<body>
    <!-- Left column -->
    <div class="templatemo-flex-row">
        <div class="templatemo-sidebar">
            <header class="templatemo-site-header">
                <div class="square"></div>
                <?php
                $Welcome = "Welcome";
                echo "<h1>" . $Welcome . "<br>" . $_SESSION['username'] . "</h1>";
                ?>
            </header>
            
            <div class="mobile-menu-icon">
                <i class="fa fa-bars"></i>
            </div>
            <nav class="templatemo-left-nav">
                <ul>
                    <li><a href="../SProfile/index.php" class="active"><i class="fa fa-home fa-fw"></i>Dashboard</a></li>
                    <li><a href="../company/display.php"><i class="fa fa-bar-chart fa-fw"></i>Companies</a></li>
                    <li><a href="../company/applications.php"><i class="fa fa-bar-chart fa-fw"></i>Applications</a></li>
                    <li><a href="../SProfile/logout.php"><i class="fa fa-eject fa-fw"></i>Sign Out</a></li>
                </ul>
            </nav>
        </div>
        <!-- Main content -->
        <div class="templatemo-content col-1 light-gray-bg">
            <div class="templatemo-top-nav-container">
                <div class="row">
                    <nav class="templatemo-top-nav col-lg-12 col-md-12">
                        <ul class="text-uppercase">
                            <li><a href="../Drives/index.php">Home AU CEG</a></li>
                            <li><a href="../Drives/products.php">Drives Homepage</a></li>
                            <li><a href="Notif.php">Notifications</a></li>
                            <li><a href="Change Password.php">Change Password</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="templatemo-content-container">
                <h2>Application Status</h2>

                <!-- Applied Applications Section -->
                <h3>Applied</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Job Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data['Applied'] as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['CompanyName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['JobName']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Rejected Applications Section -->
                <h3>Previous</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Job Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data['Rejected'] as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['CompanyName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['JobName']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Selected Applications Section -->
               

                <footer class="text-right">
                    <p>
                        &copy; <?php echo date("Y"); ?> AU CEG | Developed by 
                        <a href="http://znumerique.azurewebsites.net" target="_parent">Innovative minds of CEG</a>
                    </p>
                </footer>
            </div>
        </div>
    </div>
    <!-- JS -->
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/templatemo-script.js"></script>
</body>
</html>
