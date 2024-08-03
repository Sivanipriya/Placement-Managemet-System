<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
    exit();
}

$jobId = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

if ($jobId <= 0) {
    die("Invalid job ID.");
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "revised");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch total number of student applications for the specific job
$totalQuery = "SELECT COUNT(*) AS total FROM application WHERE Job_id = $jobId";
$totalResult = mysqli_query($conn, $totalQuery);
if (!$totalResult) {
    die("Query failed: " . mysqli_error($conn));
}
$totalRow = mysqli_fetch_assoc($totalResult);
$totalApplications = $totalRow['total'];

// Fetch student applications for the specific job
$field = isset($_GET['field']) ? mysqli_real_escape_string($conn, $_GET['field']) : 'FirstName';
$order = isset($_GET['order']) ? mysqli_real_escape_string($conn, $_GET['order']) : 'asc';

$query = "SELECT a.s_id, b.FirstName, b.LastName, b.Branch, b.resume_path, b.cv_path
          FROM application a
          INNER JOIN basicdetails b ON a.s_id = b.Id
          WHERE a.Job_id = $jobId
          ORDER BY b.$field $order";
$result = mysqli_query($conn, $query);

// Check for SQL errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'])) {
        foreach ($_POST['status'] as $studentId => $status) {
            $statusValue = ($status === 'approve') ? 1 : 0;
            $updateQuery = "UPDATE application SET Appln_status = $statusValue WHERE Job_id = $jobId AND s_id = $studentId";
            mysqli_query($conn, $updateQuery);
        }
        header("Location: applied_members.php?job_id=$jobId");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Applied Students</title>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/templatemo-style.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #eaeaea;
        }
        .sort-form {
            margin-bottom: 20px;
        }
        .details-row {
            display: none;
            background-color: #f9f9f9;
        }
        .details-row td {
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .details-row div {
            margin-bottom: 10px;
        }
        .details-row div label {
            font-weight: bold;
        }
        .toggle-btn {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
            font-size: 14px;
        }
        .toggle-btn:hover {
            color: #0056b3;
        }
    </style>
    <script>
        function toggleDetails(rowId) {
            var detailsRow = document.getElementById('details-' + rowId);
            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        }
    </script>
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
                <li><a href="jobs.php"><i class="fa fa-bar-chart fa-fw"></i>Job Listings</a></li>
                <li><a href="logout.php"><i class="fa fa-eject fa-fw"></i>Sign Out</a></li>
            </ul>
        </nav>
    </div>
    <div class="templatemo-content col-1 light-gray-bg">
        <div class="templatemo-content-container">
            <div class="templatemo-content-widget white-bg">
                <h2 class="margin-bottom-10">Applied Students</h2>
                
                <p>Total Applications: <?php echo htmlspecialchars($totalApplications); ?></p>
                
                <form class="sort-form" action="" method="GET">
                    <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($jobId); ?>">
                    <label for="field">Sort by:</label>
                    <select name="field" id="field">
                        <option value="FirstName" <?php if ($field == 'FirstName') echo 'selected'; ?>>Name</option>
                        <option value="Branch" <?php if ($field == 'Branch') echo 'selected'; ?>>Branch</option>
                        <option value="USN" <?php if ($field == 'USN') echo 'selected'; ?>>USN</option>
                        <option value="Mobile" <?php if ($field == 'Mobile') echo 'selected'; ?>>Mobile</option>
                        <option value="Email" <?php if ($field == 'Email') echo 'selected'; ?>>Email</option>
                        <option value="DOB" <?php if ($field == 'DOB') echo 'selected'; ?>>DOB</option>
                        <option value="Sem" <?php if ($field == 'Sem') echo 'selected'; ?>>Semester</option>
                        <option value="SSLC" <?php if ($field == 'SSLC') echo 'selected'; ?>>SSLC</option>
                        <option value="PU_Dip" <?php if ($field == 'PU_Dip') echo 'selected'; ?>>PU/Dip</option>
                        <option value="BE" <?php if ($field == 'BE') echo 'selected'; ?>>BE</option>
                        <option value="Backlogs" <?php if ($field == 'Backlogs') echo 'selected'; ?>>Backlogs</option>
                        <option value="HofBacklogs" <?php if ($field == 'HofBacklogs') echo 'selected'; ?>>History of Backlogs</option>
                        <option value="DetainYears" <?php if ($field == 'DetainYears') echo 'selected'; ?>>Detain Years</option>
                    </select>
                    <label for="order">Order:</label>
                    <select name="order" id="order">
                        <option value="asc" <?php if ($order == 'asc') echo 'selected'; ?>>Ascending</option>
                        <option value="desc" <?php if ($order == 'desc') echo 'selected'; ?>>Descending</option>
                    </select>
                    <input type="submit" value="Sort">
                </form>
                
                <form action="" method="POST">
                    <table border='1'>
                        <thead><tr><th>S.No</th><th>Name</th><th>Branch</th><th>Resume</th><th>Actions</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                $sno = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row["s_id"];
                                    $fullName = htmlspecialchars($row["FirstName"] . " " . $row["LastName"]);
                                    $branch = htmlspecialchars($row["Branch"]);
                                    $resumePath = htmlspecialchars($row["resume_path"]);
                                    $cvPath = htmlspecialchars($row["cv_path"]);

                                    // Fetch detailed information for the student
                                    $detailsQuery = "SELECT * FROM basicdetails WHERE Id = $id";
                                    $detailsResult = mysqli_query($conn, $detailsQuery);
                                    $detailsRow = mysqli_fetch_assoc($detailsResult);

                                    echo "<tr>";
                                    echo "<td>" . $sno . "</td>";
                                    echo "<td>" . $fullName . "</td>";
                                    echo "<td>" . $branch . "</td>";
                                    echo "<td><a href='" . $resumePath . "' target='_blank'>View</a></td>";
                                    echo "<td><a href='javascript:void(0);' class='toggle-btn' onclick='toggleDetails($id)'>Addtnal Info</a></td>";
                                    echo "<td>";
                                    echo "<select name='status[$id]'>";
                                    echo "<option value='pending'>Select Action</option>";
                                    echo "<option value='approve'>Approve</option>";
                                    echo "<option value='reject'>Reject</option>";
                                    echo "</select>";
                                    echo "</td>";
                                    echo "</tr>";

                                    // Details row
                                    echo "<tr id='details-$id' class='details-row'>";
                                    echo "<td colspan='6'>";
                                    echo "<div><label>USN:</label> " . htmlspecialchars($detailsRow["USN"]) . "</div>";
                                    echo "<div><label>Mobile:</label> " . htmlspecialchars($detailsRow["Mobile"]) . "</div>";
                                    echo "<div><label>Email:</label> " . htmlspecialchars($detailsRow["Email"]) . "</div>";
                                    echo "<div><label>DOB:</label> " . htmlspecialchars($detailsRow["DOB"]) . "</div>";
                                    echo "<div><label>Semester:</label> " . htmlspecialchars($detailsRow["Sem"]) . "</div>";
                                    echo "<div><label>Branch:</label> " . htmlspecialchars($detailsRow["Branch"]) . "</div>";
                                    echo "<div><label>SSLC:</label> " . htmlspecialchars($detailsRow["SSLC"]) . "</div>";
                                    echo "<div><label>PU/Dip:</label> " . htmlspecialchars($detailsRow["PU_Dip"]) . "</div>";
                                    echo "<div><label>BE:</label> " . htmlspecialchars($detailsRow["BE"]) . "</div>";
                                    echo "<div><label>Backlogs:</label> " . htmlspecialchars($detailsRow["Backlogs"]) . "</div>";
                                    echo "<div><label>History of Backlogs:</label> " . htmlspecialchars($detailsRow["HofBacklogs"]) . "</div>";
                                    echo "<div><label>Detain Years:</label> " . htmlspecialchars($detailsRow["DetainYears"]) . "</div>";
                                    echo "<div><label>CV:</label> <a href='" . $cvPath . "' target='_blank'>View</a></div>";
                                    echo "<a href='javascript:void(0);' class='toggle-btn' onclick='toggleDetails($id)'>Hide</a>";
                                    echo "</td>";
                                    echo "</tr>";

                                    $sno++;
                                }

                                echo "</tbody></table>";
                            } else {
                                echo "No students have applied for this job.";
                            }

                            mysqli_close($conn);
                            ?>

                            <input type="submit" value="Update Status">
                        </form>

            </div>
        </div>
        <footer class="text-right">
            <p>Copyright &copy; AU CEG | Developed by <a href="http://znumerique.azurewebsites.net" target="_parent">Innovative minds of CEG</a></p>
        </footer>
    </div>
</div>
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
