<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = isset($_POST['s_id']) ? intval($_POST['s_id']) : 0;
    $job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
    $company_id = isset($_POST['company_id']) ? intval($_POST['company_id']) : 0;
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

    // Validate input
    if ($s_id > 0 && $job_id > 0 && $company_id > 0 && ($status === 0 || $status === 1)) {
        $connect = mysqli_connect("localhost", "root", "", "revised");
        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Update application status
        $query = "UPDATE application SET Appln_status = ? WHERE s_id = ? AND Job_id = ? AND Company_id = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'iiii', $status, $s_id, $job_id, $company_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_close($connect);
    }

    // Redirect back to the previous page
    header("Location: company.php?job_id=" . urlencode($job_id) . "&company_id=" . urlencode($company_id));
    exit();
}
?>
