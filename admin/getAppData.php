<?php

// Define available years based on your data
$years = [2022, 2023, 2024]; // Adjust based on actual years

// Connect to your database
$db_host = "localhost";
$db_name = "internship";
$db_user = "root";
$db_password = "";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize data arrays
$selectedYear = isset($_GET['year']) ? (int) $_GET['year'] : $years[0]; // Get selected year or default to first
$labels = array_fill(1, 12, "");
$acceptedPerMonth = array_fill(1, 12, 0);
$rejectedPerMonth = array_fill(1, 12, 0);
$pendingPerMonth = array_fill(1, 12, 0);

// SQL query to fetch data based on selected year
$sql = "SELECT DATE_FORMAT(appdate, '%m') AS month, decision, COUNT(*) AS count 
        FROM Application 
        WHERE YEAR(appdate) = ? 
        GROUP BY month, decision";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

// Process results and populate data arrays
while ($row = $result->fetch_assoc()) {
    $month = (int)$row['month'];
    $labels[$month] = "Month $month";
    switch ($row['decision']) {
        case 'Y':
            $acceptedPerMonth[$month] = $row['count'];
            break;
        case 'N':
            $rejectedPerMonth[$month] = $row['count'];
            break;
        case 'P':
            $pendingPerMonth[$month] = $row['count'];
            break;
    }
}

// Close connection
$conn->close();

// Prepare data for Chart.js
$labels = array_map(function ($month) {
    return "Month $month";
}, range(1, 12));

$datasets = array(
    array(
        "label" => "Accepted",
        "data" => $acceptedPerMonth,
        "backgroundColor" => "green",
    ),
    array(
        "label" => "Rejected",
        "data" => $rejectedPerMonth,
        "backgroundColor" => "red",
    ),
    array(
        "label" => "Pending",
        "data" => $pendingPerMonth,
        "backgroundColor" => "yellow",
    ),
);

?>