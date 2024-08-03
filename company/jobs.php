<?php
session_start();
if (!$_SESSION["username"]) {
    header("location: index.php");
    die("You must be logged in to view this page <a href='index.php'>Click here</a>");
}

$connect = mysqli_connect("localhost", "root", "", "revised");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the logged-in company name
$companyName = $_SESSION["username"];

// Fetch job listings posted by the logged-in company
$query = "SELECT * FROM job WHERE Company_id = (SELECT Company_id FROM company WHERE Name = '$companyName')";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Job Listings</title>
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/templatemo-style.css" rel="stylesheet">
</head>
<body>
<div class="templatemo-flex-row">
  <div class="templatemo-sidebar">
    <header class="templatemo-site-header">
      <div class="square"></div>
      <?php
      $Welcome = "Welcome";
      echo "<h1>" . $Welcome . "<br>" . $_SESSION['username'] . "</h1>";
      ?>
    </header>
    <nav class="templatemo-left-nav">
      <ul>
        <li><a href="jobs.php" class="active"><i class="fa fa-bar-chart fa-fw"></i>Job Listings</a></li>
        <li><a href="logout.php"><i class="fa fa-eject fa-fw"></i>Sign Out</a></li>
      </ul>
    </nav>
  </div>
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
      <div class="templatemo-content-widget white-bg">
        <h2 class="margin-bottom-10">Job Listings</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead><tr><th>S.No</th><th>Job Name</th><th>Description</th><th>Eligibility</th><th>Action</th></tr></thead>";
            echo "<tbody>";

            // Initialize serial number counter
            $sno = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $sno . "</td>"; // Display serial number
                echo "<td>" . $row["Name"] . "</td>";
                echo "<td>" . $row["Description"] . "</td>";
                echo "<td>" . $row["Eligibility"] . "</td>";
                echo "<td><a href='applied_members.php?job_id=" . $row["Job_id"] . "' class='btn btn-primary'>View Applied Members</a></td>";
                echo "</tr>";

                // Increment serial number counter
                $sno++;
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No job listings found.</p>";
        }
        mysqli_close($connect);
        ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
