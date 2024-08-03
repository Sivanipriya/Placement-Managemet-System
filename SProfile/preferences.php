<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("location: index.php");
  die("You must be logged in to view this page <a href='index.php'>Click here</a>");
}

include 'db_connection.php'; // Include the database connection


$s_id = $_SESSION['s_id'] ?? null; // Get s_id from session

if (!$s_id) {
    die("Student ID not found.");
}

// Prepare SQL query to fetch student details
$sql = "SELECT * FROM basicdetails WHERE Id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Failed to prepare the SQL statement: " . $connect->error);
}

$stmt->bind_param('i', $s_id);
$stmt->execute();
$result = $stmt->get_result();

$student = $result->fetch_assoc();

$stmt->close();
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!--favicon-->
    <link rel="shortcut icon" href="favicon.ico" type="image/icon">
    <link rel="icon" href="favicon.ico" type="image/icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preferences</title>
    <meta name="description" content="">
    <meta name="author" content="templatemo">
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
            <form class="templatemo-search-form" role="search">
                <div class="input-group">
                    <button type="submit" class="fa fa-search"></button>
                    <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
                </div>
            </form>
            <div class="mobile-menu-icon">
                <i class="fa fa-bars"></i>
            </div>
            <nav class="templatemo-left-nav">
                <ul>
                    <li><a href="login.php"><i class="fa fa-home fa-fw"></i>Dashboard</a></li>
                    <li><a href="#"><i class="fa fa-bar-chart fa-fw"></i>Placement Drives</a></li>
                    <li><a href="#" class="active"><i class="fa fa-sliders fa-fw"></i>Preferences</a></li>
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
                            <li><a href="">Overview</a></li>
                            <li><a href="Change Password.php">Change Password</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="templatemo-content-container">
                <div class="templatemo-content-widget white-bg">
                    <h2 class="margin-bottom-10">Preferences</h2>
                    <p>Update Your Details</p>
                    <form action="pref_process.php" class="templatemo-login-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?php echo isset($student) ? 'update' : 'add'; ?>">
                        <input type="hidden" name="s_id" value="<?php echo htmlspecialchars($s_id); ?>">
                        <div class="row form-group">
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="inputFirstName">First Name</label>
                                <input type="text" name="Firstname" class="form-control" id="inputFirstName" placeholder="Ram" value="<?php echo htmlspecialchars($student['FirstName'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="inputLastName">Last Name</label>
                                <input type="text" name="Lastname" class="form-control" id="inputLastName" placeholder="Laxman" value="<?php echo htmlspecialchars($student['LastName'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="usn">USN</label>
                                <input type="text" name="USN" class="form-control" id="usn" placeholder="1CG12IS000" value="<?php echo htmlspecialchars($student['USN'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="Phone">Phone</label>
                                <input type="text" name="Mobile" class="form-control" id="Phone" placeholder="91xxxxxxxx" value="<?php echo htmlspecialchars($student['Mobile'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="Email">Email</label>
                                <input type="email" name="Email" class="form-control" id="Email" placeholder="abc@example.com" value="<?php echo htmlspecialchars($student['Email'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="DOB">Date of Birth</label>
                                <input type="date" name="DOB" class="form-control" id="DOB" placeholder="DD/MM/YYYY" value="<?php echo htmlspecialchars($student['DOB'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label class="control-label templatemo-block">Current Semester</label>
                                <select name="Sem" class="form-control">
                                    <option value="select">Semester</option>
                                    <?php for ($i = 1; $i <= 8; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($student['Sem'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label class="control-label templatemo-block">Branch of Study</label>
                                <select name="Branch" class="form-control">
                                    <option value="select">Branch</option>
                                    <?php
                                    $branches = ['BScience' => 'Basic Science', 'IT' => 'IT', 'CSE' => 'CSE', 'EEE' => 'EEE', 'ECE' => 'ECE', 'ME' => 'ME', 'CVE' => 'CVE'];
                                    foreach ($branches as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo ($student['Branch'] == $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="sslc">SSLC/10th Aggregate</label>
                                <input type="text" name="SSLC" class="form-control" id="sslc" placeholder="" value="<?php echo htmlspecialchars($student['SSLC'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="Pu">12th/Diploma Aggregate</label>
                                <input type="text" name="PU_Dip" class="form-control" id="Pu" placeholder="" value="<?php echo htmlspecialchars($student['PU_Dip'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="BE">BE Aggregate</label>
                                <input type="text" name="BE" class="form-control" id="BE" placeholder="" value="<?php echo htmlspecialchars($student['BE'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="Backlogs">Backlogs</label>
                                <input type="text" name="Backlogs" class="form-control" id="Backlogs" placeholder="" value="<?php echo htmlspecialchars($student['Backlogs'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="HofBacklogs">History of Backlogs</label>
                                <input type="text" name="HofBacklogs" class="form-control" id="HofBacklogs" placeholder="" value="<?php echo htmlspecialchars($student['HofBacklogs'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="DetainYears">Detain Years</label>
                                <input type="text" name="DetainYears" class="form-control" id="DetainYears" placeholder="" value="<?php echo htmlspecialchars($student['DetainYears'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="resume">Resume URL</label>
                                <input type="url" name="resume_path" id="resume" class="form-control" placeholder="Enter resume URL" value="<?php echo htmlspecialchars($student['resume_path'] ?? ''); ?>">
                                <p>Provide a link to your resume (e.g., Google Drive, Dropbox).</p>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="cv">CV URL</label>
                                <input type="url" name="cv_path" id="cv" class="form-control" placeholder="Enter CV URL" value="<?php echo htmlspecialchars($student['cv_path'] ?? ''); ?>">
                                <p>Provide a link to your CV (e.g., Google Drive, Dropbox).</p>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <?php if ($student): ?>
                                <!-- s_id found -->
                                <button type="submit" name="update" class="templatemo-blue-button">Update</button>
                            <?php else: ?>
                                <!-- s_id not found -->
                                <button type="submit" name="submit" class="templatemo-blue-button">Add</button>
                            <?php endif; ?>
                            <button type="reset" class="templatemo-white-button">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- JS -->
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>
    <script type="text/javascript" src="js/templatemo-script.js"></script>
</body>

</html>
