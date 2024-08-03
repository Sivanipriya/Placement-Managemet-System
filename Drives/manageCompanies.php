<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.html");
    exit();
}

include('db_connection.php'); // Include the database connection file

// Fetch all company records from the database
$query = "SELECT * FROM companies";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Companies</title>
    <link rel="stylesheet" href="manageCompanies.css">
</head>
<body>
    <div class="container">
        <h2>Manage Companies</h2>
        <a href="add_company.php" class="add-button">Add New Company</a> <!-- Link to add a new company -->
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Website Link</th>
                <th>Actions</th>
            </tr>
            <?php while ($company = $result->fetch_assoc()): ?> <!-- Loop through each company record -->
            <tr>
                <td><?php echo htmlspecialchars($company['company_id']); ?></td>
                <td><?php echo htmlspecialchars($company['name']); ?></td>
                <td><?php echo htmlspecialchars($company['description']); ?></td>
                <td><a href="<?php echo htmlspecialchars($company['website_link']); ?>" target="_blank"><?php echo htmlspecialchars($company['website_link']); ?></a></td>
                <td>
                <a href="delete_company.php?id=<?php echo $company['company_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this company?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
