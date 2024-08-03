<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Profile Home</title>
    <meta name="description" content="">
    <meta name="author" content="templatemo">
    <!--favicon-->
    <link rel="shortcut icon" href="favicon.ico" type="image/icon">
    <link rel="icon" href="favicon.ico" type="image/icon">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/templatemo-style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .company {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .job {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .apply-btn {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            border: none;
        }
    </style>
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
                    <li><a href="home.php" class="active"><i class="fa fa-home fa-fw"></i>Dashboard</a></li>
                    <li><a href="display.php"><i class="fa fa-bar-chart fa-fw"></i>Companies</a></li>
                    <li><a href="applications.php"><i class="fa fa-bar-chart fa-fw"></i>Applications</a></li>
                    <li><a href="logout.php"><i class="fa fa-eject fa-fw"></i>Sign Out</a></li>
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
                <div class="templatemo-flex-row flex-content-row">
                    <div class="templatemo-content-widget white-bg col-2">
                        <i class="fa fa-times"></i>
                        <div class="square"></div>
                        <h2 class="templatemo-inline-block">Job Listings</h2>
                        <hr>
                        <?php
                        // Database connection
                        $conn = mysqli_connect("localhost", "root", "", "revised");
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        // Fetch companies and their jobs
                        $sql = "SELECT c.company_id, c.name AS company_name, j.job_id, j.name AS job_name, j.description, j.eligibility 
                                FROM company c
                                INNER JOIN job j ON c.company_id = j.company_id";
                        $result = mysqli_query($conn, $sql);

                        // Display companies and their jobs
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='company'>";
                                echo "<h3>" . htmlspecialchars($row['company_name']) . "</h3>";
                                echo "<div class='job'>";
                                echo "<h4>" . htmlspecialchars($row['job_name']) . "</h4>";
                                echo "<p>Description: " . htmlspecialchars($row['description']) . "</p>";
                                echo "<p>Eligibility: " . htmlspecialchars($row['eligibility']) . "</p>";
                                echo "<form action='../SProfile/preferences.php' method='get'>";
                                echo "<input type='hidden' name='company_id' value='" . htmlspecialchars($row['company_id']) . "'>";
                                echo "<input type='hidden' name='job_id' value='" . htmlspecialchars($row['job_id']) . "'>";
                                echo "<button type='submit' class='apply-btn'>Apply</button>";
                                echo "</form>";
                                echo "</div>"; // End of job div
                                echo "</div>"; // End of company div
                            }
                        } else {
                            echo "No jobs available.";
                        }

                        // Close connection
                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
                <footer class="text-right">
                    <p>Copyright &copy; AU CEG | Developed by
                        <a href="http://znumerique.azurewebsites.net" target="_parent">Innovative minds of CEG </a>
                    </p>
                </footer>
            </div>
        </div>
    </div>
    <!-- JS -->
    <script src="js/jquery-1.11.2.min.js"></script>
    <!-- jQuery -->
    <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <!-- jQuery Migrate Plugin -->
    <script type="text/javascript" src="js/templatemo-script.js"></script>
    <!-- Templatemo Script -->
</body>
</html>
