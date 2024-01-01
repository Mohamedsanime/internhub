<?php
// Database connection parameters
$host = 'localhost'; 
$username = 'username'; 
$password = 'password'; 
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['year'])) {
    $selectedYear = intval($_GET['year']);
    $query = "SELECT MONTH(fromdate) AS month, COUNT(*) AS offerCount 
              FROM offers 
              WHERE YEAR(fromdate) = '$selectedYear' 
              GROUP BY MONTH(fromdate)";
    $result = $conn->query($query);

    $offerData = [];
    while ($row = $result->fetch_assoc()) {
        $offerData[] = [$row['month'], intval($row['offerCount'])];
    }

    echo json_encode($offerData);
}

$conn->close();
?>
