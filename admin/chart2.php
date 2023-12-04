<!DOCTYPE html>
<html>
<head>
    <title>Application Bar Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Application Bar Chart</h1>
    <form>
        <label for="year">Select Year:</label>
        <select name="year" id="year">
            <!-- Populate this dropdown with available years from your database -->
            <?php
            $currentYear = date("Y");
            for ($year = $currentYear; $year >= 2000; $year--) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
        </select>
    </form>
    <canvas id="applicationChart"></canvas>

    <script>
        // JavaScript code for generating the chart
        var ctx = document.getElementById('applicationChart').getContext('2d');
        var chart = null;

        // Function to fetch and update the chart data
        function updateChart(selectedYear) {
            // Replace this with your database connection code
            $db_host = "localhost";
$db_name = "internship";
$db_user = "root";
$db_password = "";
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
            // Query to retrieve data for the selected year
            var url = 'getChartData.php?year=' + selectedYear;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (chart !== null) {
                        chart.destroy(); // Destroy the previous chart instance
                    }
                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.months,
                            datasets: [
                                {
                                    label: 'Accepted',
                                    data: data.accepted,
                                    backgroundColor: 'green'
                                },
                                {
                                    label: 'Rejected',
                                    data: data.rejected,
                                    backgroundColor: 'red'
                                },
                                {
                                    label: 'Pending',
                                    data: data.pending,
                                    backgroundColor: 'yellow'
                                }
                            ]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Listen for the change event on the year select element
        document.getElementById('year').addEventListener('change', function() {
            var selectedYear = this.value;
            updateChart(selectedYear);
        });

        // Initial chart generation with the default selected year
        var initialSelectedYear = document.getElementById('year').value;
        updateChart(initialSelectedYear);
    </script>

    <?php
    // PHP code for handling the initial data retrieval (getChartData.php)
    if (isset($_GET['year'])) {
        $selectedYear = $_GET['year'];
        
        // Replace this with your database connection code
        // ...

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

        // Encode the data as JSON for JavaScript
        $chartData = [
            'months' => $months,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'pending' => $pending
        ];

        echo "<script>";
        echo "var initialChartData = " . json_encode($chartData) . ";";
        echo "</script>";
    }
    ?>
</body>
</html>
