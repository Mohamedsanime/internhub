<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected year from the form
    $selectedYear = $_POST['year'];
// Define the year to be analyzed
//$selectedYear = 2023; // Replace with your chosen year

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
$acceptedPerMonth = array_fill(1, 12, 0);
$rejectedPerMonth = array_fill(1, 12, 0);
$pendingPerMonth = array_fill(1, 12, 0);

// SQL query to fetch data
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Decisions Per Month</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
</head>
<body>
    <h1>Application Decisions for <?php ; ?></h1>
    <form method="POST" action="">
        <label for="year">Select Year:</label>
        <select name="year" id="year">
            <!-- Populate this dropdown with available years from your database -->
            <?php
            $currentYear = date("Y");
            for ($year = $currentYear; $year >= 2022; $year--) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
        </select>
        <input type="submit" value="Generate Chart">
    </form>

    <canvas id="myChart"></canvas>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: <?php echo json_encode($datasets); ?>
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Number of Applications per Decision'
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false
                }
            }
        });
    </script>
</body>
</html>