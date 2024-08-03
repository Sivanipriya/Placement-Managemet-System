<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Retrieve POST data
$s_id = $_POST['s_id'] ?? null; // Retrieve s_id from POST data
$action = $_POST['action'] ?? null; // Retrieve action from POST data
error_log("Received action: " . $action); // Debug line
// Validate action
if (!in_array($action, ['add', 'update'])) {
    die("Invalid action specified. The action should be 'add' or 'update'.");
}

// Check if $s_id is provided for update action
if ($action === 'update' && !$s_id) {
    die("Student ID is missing for update.");
}

// Prepare and execute SQL based on action
if ($action === 'add') {
    // Prepare SQL for inserting a new record
   $sql = "UPDATE basicdetails SET FirstName = ?, LastName = ?, USN = ?, Mobile = ?, Email = ?, DOB = ?, Sem = ?, Branch = ?, SSLC = ?, PU_Dip = ?, BE = ?, Backlogs = ?, HofBacklogs = ?, DetainYears = ?, resume_path = ?, cv_path = ? WHERE Id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Failed to prepare the SQL statement: " . $conn->error);
}

$stmt->bind_param(
    'sssisssddddiiisi', // 3 's' (string), 1 'i' (int), 4 'd' (float), 5 'i' (int), 2 's' (string) + 1 'i' for Id
    $_POST['Firstname'], 
    $_POST['Lastname'], 
    $_POST['USN'], 
    $_POST['Mobile'], 
    $_POST['Email'], 
    $_POST['DOB'], 
    $_POST['Sem'], 
    $_POST['Branch'], 
    $_POST['SSLC'], 
    $_POST['PU_Dip'], 
    $_POST['BE'], 
    $_POST['Backlogs'], 
    $_POST['HofBacklogs'], 
    $_POST['DetainYears'], 
    $_POST['resume_path'], 
    $_POST['cv_path'], 
    $s_id
);

} elseif ($action === 'update') {
    // Prepare SQL for updating an existing record
    $sql = "UPDATE basicdetails SET FirstName = ?, LastName = ?, USN = ?, Mobile = ?, Email = ?, DOB = ?, Sem = ?, Branch = ?, SSLC = ?, PU_Dip = ?, BE = ?, Backlogs = ?, HofBacklogs = ?, DetainYears = ?, resume_path = ?, cv_path = ? WHERE Id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Failed to prepare the SQL statement: " . $conn->error);
    }
    
    $stmt->bind_param(
        'sssisssddddiiisi', // 3 's' (string), 1 'i' (int), 4 'd' (float), 5 'i' (int), 2 's' (string) + 1 'i' for Id
        $_POST['Firstname'], 
        $_POST['Lastname'], 
        $_POST['USN'], 
        $_POST['Mobile'], 
        $_POST['Email'], 
        $_POST['DOB'], 
        $_POST['Sem'], 
        $_POST['Branch'], 
        $_POST['SSLC'], 
        $_POST['PU_Dip'], 
        $_POST['BE'], 
        $_POST['Backlogs'], 
        $_POST['HofBacklogs'], 
        $_POST['DetainYears'], 
        $_POST['resume_path'], 
        $_POST['cv_path'], 
        $s_id
    );
}    

// Execute the statement
if ($stmt->execute()) {
    echo "Record successfully " . ($action === 'add' ? "added" : "updated");
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
