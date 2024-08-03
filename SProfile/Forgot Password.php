<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <form action="send_reset_link.php" method="POST">
        <label for="usn">USN:</label>
        <input type="text" id="usn" name="usn" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
