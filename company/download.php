<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']); // Decode the file URL

    // Validate the URL to ensure it is a Google Drive link
    if (filter_var($file, FILTER_VALIDATE_URL) && strpos($file, 'drive.google.com') !== false) {
        header("Location: $file"); // Redirect to the Google Drive link
        exit();
    } else {
        echo "Invalid file link.";
    }
} else {
    echo "No file specified.";
}
?>
