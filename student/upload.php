<?php
$uploadSuccess = false;
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDir = "C:/xampp/htdocs/uploads/";
    $filePath = $targetDir . basename($_FILES["fileToUpload"]["name"]);

    // Database connection parameters
    $dbHost = "localhost";
    $dbUsername = "root"; // replace with your database username
    $dbPassword = ""; // replace with your database password
    $dbName = "internship";     // replace with your database name

    // Create database connection
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Attempt to upload file
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath)) {
        $uploadSuccess = true;

        // Prepare SQL statement to insert file path into the database
        //$stmt = $db->prepare("INSERT INTO files (path) VALUES (?)");
       // $stmt->bind_param("s", $filePath);
        //$stmt->execute();
    } else {
        $errorMessage = "Sorry, there was an error uploading your file.";
    }

    // Close database connection
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Upload</title>
    <script>
        function previewFile() {
            const preview = document.getElementById('file-preview');
            const file = document.querySelector('input[type=file]').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                // Convert file to base64 string for preview
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>
    <?php
    if ($uploadSuccess) {
        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded. File path stored in database.";
    } elseif (!empty($errorMessage)) {
        echo $errorMessage;
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="previewFile()">
        <img id="file-preview" src="#" alt="Image preview" style="display: none; max-width: 200px; max-height: 200px;"/>
        <input type="submit" value="Upload File" name="submit">
    </form>
</body>
</html>
