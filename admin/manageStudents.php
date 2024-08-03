<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.html");
    exit();
}

include('db_connection.php');

// Fetch all student records from the database
$query = "SELECT * FROM students";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="manageStudents.css">
</head>
<body>
    <div class="container">
        <h2>Manage Students</h2>
        <a href="add_student.php" class="add-button">Add New Student</a>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>USN</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>DOB</th>
                        <th>Semester</th>
                        <th>Branch</th>
                        <th>SSLC %</th>
                        <th>Diploma %</th>
                        <th>BE CGPA</th>
                        <th>Backlogs</th>
                        <th>Detain Years</th>
                        <th>Resume Path</th>
                        <th>Cover Letter Path</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['id']); ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['usn']); ?></td>
                        <td><?php echo htmlspecialchars($student['mobile']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['dob']); ?></td>
                        <td><?php echo htmlspecialchars($student['semester']); ?></td>
                        <td><?php echo htmlspecialchars($student['branch']); ?></td>
                        <td><?php echo htmlspecialchars($student['sslc_percent']); ?></td>
                        <td><?php echo htmlspecialchars($student['diploma_percent']); ?></td>
                        <td><?php echo htmlspecialchars($student['be_cgpa']); ?></td>
                        <td><?php echo htmlspecialchars($student['backlogs']); ?></td>
                        <td><?php echo htmlspecialchars($student['detain_years']); ?></td>
                        <td><?php echo htmlspecialchars($student['resume_path']); ?></td>
                        <td><?php echo htmlspecialchars($student['cover_letter_path']); ?></td>
                        <td class="actions">
                            <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
