<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.html");
    exit();
}

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $website_link = $_POST['website_link'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO companies (name, description, website_link, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $description, $website_link, $password);

    if ($stmt->execute()) {
        header("Location: manageCompanies.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Company</title>
    <link rel="stylesheet" href="add_company.css">
</head>
<body>
    <div class="container">
        <h2>Add Company</h2>
        <form action="add_company.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="website_link">Website Link:</label>
            <input type="url" id="website_link" name="website_link" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Add">
        </form>
    </div>
</body>
</html>
