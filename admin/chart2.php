<!DOCTYPE html>
<html>
<head>
    <title>Application Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<select id="yearSelector">
    <!-- JavaScript will populate this -->
</select>

<div class="box">
    <canvas id="applicationChart"></canvas>
</div>

<script>
$(document).ready(function() {
    // Populate years in dropdown
    for (let year = 2020; year <= new Date().getFullYear(); year++) {
        $('#yearSelector').append(new Option(year, year));
    }

    var ctx = document.getElementById('applicationChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Accepted',
                backgroundColor: 'green',
                data: []
            }, {
                label: 'Rejected',
                backgroundColor: 'red',
                data: []
            }, {
                label: 'Pending',
                backgroundColor: 'blue',
                data: []
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function updateChart(year) {
        $.ajax({
            url: 'fetch_data.php',
            type: 'GET',
            data: {year: year},
            success: function(response) {
                var data = JSON.parse(response);
                var accepted = new Array(12).fill(0);
                var rejected = new Array(12).fill(0);
                var pending = new Array(12).fill(0);

                data.forEach(function(row) {
                    accepted[row.month - 1] = row.accepted;
                    rejected[row.month - 1] = row.rejected;
                    pending[row.month - 1] = row.pending;
                });

                chart.data.datasets[0].data = accepted;
                chart.data.datasets[1].data = rejected;
                chart.data.datasets[2].data = pending;
                chart.update();
            }
        });
    }

    $('#yearSelector').change(function() {
        updateChart(this.value);
    });

    // Initialize chart with current year
    updateChart(new Date().getFullYear());
});
</script>

</body>
</html>
