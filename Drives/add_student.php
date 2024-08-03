<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.html");
    exit();
}

include('db_connection.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $usn = $_POST['usn'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $semester = $_POST['semester'];
    $branch = $_POST['branch'];
    $sslc_percent = $_POST['sslc_percent'];
    $diploma_percent = empty($_POST['diploma_percent']) ? null : $_POST['diploma_percent'];
    $be_cgpa = $_POST['be_cgpa'];
    $backlogs = $_POST['backlogs'];
    $detain_years = $_POST['detain_years'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handling file uploads
    $resume_path = '';
    $cover_letter_path = '';

    if (isset($_FILES['resume']['name']) && $_FILES['resume']['error'] == 0) {
        $resume_path = 'uploads/' . basename($_FILES['resume']['name']);
        move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path);
    }

    if (isset($_FILES['cover_letter']['name']) && $_FILES['cover_letter']['error'] == 0) {
        $cover_letter_path = 'uploads/' . basename($_FILES['cover_letter']['name']);
        move_uploaded_file($_FILES['cover_letter']['tmp_name'], $cover_letter_path);
    }

    $query = "INSERT INTO students (name, usn, mobile, email, dob, semester, branch, sslc_percent, diploma_percent, be_cgpa, backlogs, detain_years, resume_path, cover_letter_path, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssissss", $name, $usn, $mobile, $email, $dob, $semester, $branch, $sslc_percent, $diploma_percent, $be_cgpa, $backlogs, $detain_years, $resume_path, $cover_letter_path, $password);

    if ($stmt->execute()) {
        header("Location: manageStudents.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="add_student.css">
</head>
<body>
    <div class="container">
        <h2>Add Student</h2>
        <form action="add_student.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="usn">USN:</label>
            <input type="text" id="usn" name="usn" required>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>

            <label for="semester">Semester:</label>
            <input type="number" id="semester" name="semester" required>

            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" required>

            <label for="sslc_percent">SSLC %:</label>
            <input type="text" id="sslc_percent" name="sslc_percent" required>

            <label for="diploma_percent">Diploma % (optional):</label>
            <input type="text" id="diploma_percent" name="diploma_percent">

            <label for="be_cgpa">BE CGPA:</label>
            <input type="text" id="be_cgpa" name="be_cgpa" required>

            <label for="backlogs">Backlogs:</label>
            <input type="number" id="backlogs" name="backlogs" required>

            <label for="detain_years">Detain Years:</label>
            <input type="number" id="detain_years" name="detain_years" required>

            <label for="resume">Resume:</label>
            <input type="file" id="resume" name="resume" required>

            <label for="cover_letter">Cover Letter:</label>
            <input type="file" id="cover_letter" name="cover_letter" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Add">
        </form>
    </div>
</body>
</html>
