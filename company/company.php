<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Student Details</title>
<style>
    /* Existing styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        margin-top: 0;
    }

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

    form {
        margin-bottom: 20px;
    }

    form label {
        font-weight: bold;
        margin-right: 10px;
    }

    form select {
        margin-right: 10px;
        padding: 5px;
        border-radius: 5px;
    }

    form input[type="submit"] {
        padding: 5px 10px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    form input[type="submit"]:hover {
        background-color: #45a049;
    }

    .logout {
        float: right;
    }

    .details-row {
        display: none;
        background-color: #f2f2f2;
    }

    .details-row td {
        padding: 5px 10px;
        border-top: 1px solid #ddd;
    }

    .toggle-btn {
        cursor: pointer;
        color: blue;
        text-decoration: underline;
    }

    .toggle-btn:hover {
        color: darkblue;
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

// Fetch all student details from the basicdetails table
$query = "SELECT * FROM basicdetails";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) > 0) {
    // Sorting functionality
    $field = isset($_GET['field']) ? $_GET['field'] : 'BE';
    $order = isset($_GET['order']) ? $_GET['order'] : 'asc';

    // Construct SQL query for sorting
    $orderBy = "ORDER BY $field $order";
    $sortedQuery = $query . " " . $orderBy;
    $sortedResult = mysqli_query($connect, $sortedQuery);

    // Display table headers with sorting options
    echo "<h2>All Student Details</h2>";
    echo "<form action='' method='GET'>";
    echo "<label>Sort by:</label>";
    echo "<select name='field'>";
    echo "<option value='SSLC'>SSLC</option>";
    echo "<option value='PU_Dip'>PU/Dip</option>";
    echo "<option value='BE'>BE</option>";
    echo "<option value='Backlogs'>Backlogs</option>";
    echo "<option value='HofBacklogs'>History of Backlogs</option>";
    echo "<option value='DetainYears'>Detain Years</option>";
    echo "</select>";

    echo "<label>Order:</label>";
    echo "<select name='order'>";
    echo "<option value='asc'>Ascending</option>";
    echo "<option value='desc'>Descending</option>";
    echo "</select>";

    echo "<input type='submit' value='Sort'>";
    echo "</form>";

    // Display sorted table
    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
    echo "<th>USN</th>";
    echo "<th>Mobile</th>";
    echo "<th>Email</th>";
    echo "<th>DOB</th>";
    echo "<th>Sem</th>";
    echo "<th>Branch</th>";
    echo "<th>SSLC</th>";
    echo "<th>PU/Dip</th>";
    echo "<th>BE</th>";
    echo "<th>Backlogs</th>";
    echo "<th>History of Backlogs</th>";
    echo "<th>Detain Years</th>";
    echo "<th>Resume</th>";
    echo "<th>CV</th>";
    echo "<th>Actions</th>";
    echo "</tr>";

    // Output data of each row
    while ($row = mysqli_fetch_assoc($sortedResult)) {
        $id = $row["Id"];
        echo "<tr>";
        echo "<td>" . $row["Id"] . "</td>";
        echo "<td>" . $row["FirstName"] . "</td>";
        echo "<td>" . $row["LastName"] . "</td>";
        echo "<td>" . $row["USN"] . "</td>";
        echo "<td>" . $row["Mobile"] . "</td>";
        echo "<td>" . $row["Email"] . "</td>";
        echo "<td>" . $row["DOB"] . "</td>";
        echo "<td>" . $row["Sem"] . "</td>";
        echo "<td>" . $row["Branch"] . "</td>";
        echo "<td>" . $row["SSLC"] . "</td>";
        echo "<td>" . $row["PU_Dip"] . "</td>";
        echo "<td>" . $row["BE"] . "</td>";
        echo "<td>" . $row["Backlogs"] . "</td>";
        echo "<td>" . $row["HofBacklogs"] . "</td>";
        echo "<td>" . $row["DetainYears"] . "</td>";
        echo "<td><a href='download.php?file=" . urlencode($row["resume_path"]) . "'>Download</a></td>";
        echo "<td><a href='download.php?file=" . urlencode($row["cv_path"]) . "'>Download</a></td>";

        // Add toggle button and details row
        echo "<td>";
        echo "<a href='javascript:void(0);' class='toggle-btn' onclick='toggleDetails($id)'>View More</a>";
        echo "</td>";
        echo "</tr>";

        // Details row
        echo "<tr id='details-$id' class='details-row'>";
        echo "<td colspan='17'>";
        echo "<strong>USN:</strong> " . htmlspecialchars($row["USN"]) . "<br>";
        echo "<strong>Mobile:</strong> " . htmlspecialchars($row["Mobile"]) . "<br>";
        echo "<strong>Email:</strong> " . htmlspecialchars($row["Email"]) . "<br>";
        echo "<strong>DOB:</strong> " . htmlspecialchars($row["DOB"]) . "<br>";
        echo "<strong>Semester:</strong> " . htmlspecialchars($row["Sem"]) . "<br>";
        echo "<strong>Branch:</strong> " . htmlspecialchars($row["Branch"]) . "<br>";
        echo "<strong>SSLC:</strong> " . htmlspecialchars($row["SSLC"]) . "<br>";
        echo "<strong>PU/Dip:</strong> " . htmlspecialchars($row["PU_Dip"]) . "<br>";
        echo "<strong>BE:</strong> " . htmlspecialchars($row["BE"]) . "<br>";
        echo "<strong>Backlogs:</strong> " . htmlspecialchars($row["Backlogs"]) . "<br>";
        echo "<strong>History of Backlogs:</strong> " . htmlspecialchars($row["HofBacklogs"]) . "<br>";
        echo "<strong>Detain Years:</strong> " . htmlspecialchars($row["DetainYears"]) . "<br>";
        echo "<a href='javascript:void(0);' class='toggle-btn' onclick='toggleDetails($id)'>Hide</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

mysqli_close($connect);
?>
</body>
</html>
