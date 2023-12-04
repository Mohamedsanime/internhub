<?php
include('getAppData.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Decisions per Month</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Application Decisions by Month</h3>
            <div class="card-tools">
                <select id="year-selector" onchange="updateChart()">
                    <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year; ?>" <?php if ($year === $selectedYear): ?>selected<?php endif; ?>><?php echo $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-body">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');

        // Update chart based

        function updateChart() {
            const selectedYear = document.getElementById("year-selector").value;

            // AJAX request to fetch updated data based on selected year
            $.ajax({
                url: "applications_data.php", // Replace with your data fetching script
                data: { year: selectedYear },
                dataType: "json",
                success: function(data) {
                    // Update data arrays with new data
                    acceptedPerMonth = data.accepted;
                    rejectedPerMonth = data.rejected;
                    pendingPerMonth = data.pending;

                    // Update Chart.js data
                    myChart.data.datasets[0].data = acceptedPerMonth;
                    myChart.data.datasets[1].data = rejectedPerMonth;
                    myChart.data.datasets[2].data = pendingPerMonth;

                    // Update labels if year changes
                    if (myChart.data.labels[0].indexOf(selectedYear) === -1) {
                        myChart.data.labels = data.labels;
                    }

                    // Update chart and redraw
                    myChart.update();
                },
                error: function(error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        // Initialize Chart.js with initial data
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
                    text: 'Number of Applications per Decision - Year <?php echo $selectedYear; ?>'
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false
                }
            }
        });

        // Call updateChart on initial load
        updateChart();
    </script>
</body>
</html>


