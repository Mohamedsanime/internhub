<?php

// Connect to your database
// Same credentials as main script (replace with yours)
$db_host = "localhost";
$db_name = "internship";
$db_user = "root";
$db_password = "";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected year from request parameter
$selectedYear = isset($_GET['year']) ? (int) $_GET['year'] : die("Missing year parameter");

// Initialize data arrays
$labels = array_fill(1, 12, "");
$acceptedPerMonth = array_fill(1, 12, 0);
$rejectedPerMonth = array_fill(1, 12, 0);
$pendingPerMonth = array_fill(1, 12, 0);

// SQL query to fetch data and labels based on selected year
$sql = "SELECT DATE_FORMAT(appdate, '%m') AS month,
               decision, COUNT(*) AS count
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

// Prepare and send response data as JSON
$response = array(
    "labels" => $labels,
    "accepted" => $acceptedPerMonth,
    "rejected" => $rejectedPerMonth,
    "pending" => $pendingPerMonth
);

header('Content-Type: application/json');
echo json_encode($response);

?>

