<?php
if (isset($_GET['year'])) {
    $selectedYear = $_GET['year'];

    // Replace this with your database connection code
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'internship';

    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to retrieve data for the selected year
    $sql = "SELECT MONTH(appdate) AS month,
                    SUM(CASE WHEN decision = 'Y' THEN 1 ELSE 0 END) AS accepted,
                    SUM(CASE WHEN decision = 'N' THEN 1 ELSE 0 END) AS rejected,
                    SUM(CASE WHEN decision = 'P' THEN 1 ELSE 0 END) AS pending
            FROM Application
            WHERE YEAR(appdate) = $selectedYear
            GROUP BY MONTH(appdate)";

    $result = $conn->query($sql);

    $months = [];
    $accepted = [];
    $rejected = [];
    $pending = [];

    while ($row = $result->fetch_assoc()) {
        $months[] = date("F", strtotime("2023-" . $row['month'] . "-01"));
        $accepted[] = $row['accepted'];
        $rejected[] = $row['rejected'];
        $pending[] = $row['pending'];
    }

    // Close the database connection
    $conn->close();

    // Encode the data as JSON and send it back to the main HTML file
    $chartData = [
        'months' => $months,
        'accepted' => $accepted,
        'rejected' => $rejected,
        'pending' => $pending
    ];

    header('Content-Type: application/json');
    echo json_encode($chartData);
}
?>
