<?php
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
#include('getAppData.php');
$host = 'localhost';  
$username = 'root';  
$password = '';  
$dbname = 'internship';  

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a specific year is selected, otherwise use the current year
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// SQL query to fetch offer count by month for the selected year
$query = "SELECT MONTH(fromdate) AS month, COUNT(*) AS offerCount 
          FROM offers 
          WHERE YEAR(fromdate) = '$selectedYear' 
          GROUP BY MONTH(fromdate)";

$result = $conn->query($query);

$offerData = [];
while ($row = $result->fetch_assoc()) {
    $offerData[] = [$row['month'], intval($row['offerCount'])];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Offers'],
                <?php
                    foreach ($offerData as $data) {
                        echo "['" . date('F', mktime(0, 0, 0, $data[0], 10)) . "', " . $data[1] . "],";
                    }
                ?>
            ]);

            var options = {
                title: 'Offers by Month in <?php echo $selectedYear; ?>',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>

    <style>
    canvas {
      border: 1px dotted red;
    }

    .chart-container {
      position: relative;
      margin: auto;
      height: 80vh;
      width: 80vw;
    }
    </style>
</head>
<body>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Admin Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
                <?php
                    require "../config.php";
                    $query= $db->prepare("SELECT * FROM users WHERE rol_id=:vid");
                    $query->execute([
                            "vid"=>4
                    ]);
                    $student_number = $query->rowCount();
                ?>
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo $student_number ?></h3>
                  <p>Registered Students</p>
                </div>
                <div class="icon">
                  <i class="fa-solid fa-user-graduate"></i>
                </div>
                <a href="#" class="small-box-footer">
                   More information <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <?php
              $query= $db->prepare("SELECT * FROM users WHERE rol_id=:vid");
              $query->execute([
                  "vid"=>2
              ]);
              $unisup_number = $query->rowCount();
              ?>
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $unisup_number ?></h3>
                  <p>Registered Coordinators</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <?php
              $query= $db->prepare("SELECT * FROM users WHERE rol_id=:vid");
              $query->execute([
                  "vid"=>3
              ]);
              $compsup_number = $query->rowCount();
              ?>
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                  <h3><?php echo $compsup_number ?></h3>
                  <p>Registered Supervisors</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <?php
              $query= $db->prepare("SELECT * FROM internship WHERE date('d-m-Y') >=  fromdate && date('d-m-Y') <= todate");

              $query->execute();
              $internship_number = $query->rowCount();
            ?>
            <div class="small-box bg-danger">
              <div class="inner">
                  <h3><?php echo $internship_number ?></h3>
                  <p>Ongoing Internships</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Application Decisions by Month</b></h3>
                <div class="card-tools">
                  <select id="yearSelector">
                      <option value="2020">2020</option>
                      <option value="2021">2021</option>
                      <option value="2022">2022</option>
                      <option value="2023" selected>2023</option>
                      <option value="2024">2024</option>

                  </select>
                </div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="card-body">
                  <canvas id="applicationChart"></canvas>
                </div>
            </div>

            <!-- /.card -->

           
            <!-- /.card -->
          </section>
          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Internship Offers per Month</b></h3>
                <div class="card-tools">
                  <select id="yearSelector2">
                      <option value="2020">2020</option>
                      <option value="2021">2021</option>
                      <option value="2022">2022</option>
                      <option value="2023" selected>2023</option>
                      <option value="2024">2024</option>

                  </select>
                </div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="card-body">
                  <canvas id="applicationChart2"></canvas>
                </div>
            </div>

            <!-- /.card -->

           
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- Map card -->
           
            <!-- /.card -->

            

            <!-- Calendar -->
            <div class="card bg-gradient-success">
              <div class="card-header border-0">

                <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                  <!-- button with a dropdown -->
                  <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                      <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item">Add new event</a>
                      <a href="#" class="dropdown-item">Clear events</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item">View calendar</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <!--The calendar -->
                <div id="calendar" style="width: 100%"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
</div>
<script>
        $(document).ready(function() {
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
                url: 'getAppData.php',
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

        // Initialize chart with the first year in the selector
        updateChart($('#yearSelector').val());
    });
  </script>
  <script>
        $(document).ready(function() {
        var ctx = document.getElementById('applicationChart2').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: []
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
                url: 'getOfferData.php',
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

        // Initialize chart with the first year in the selector
        updateChart($('#yearSelector').val());
    });
  </script>
<?php
include('includes/footer.php');
?>
</body>
</html>