<?php
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('../admin/includes/sidebar.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$connname = 'internship';

$conn = new mysqli($host, $username, $password, $connname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Check if user is logged in and a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$student_id = $_SESSION['user_id'];

// Query for Experiences
$experiences_query = "SELECT * FROM experiences WHERE student_id = '$student_id'";
$experiences_result = $conn->query($experiences_query);

// ... similar queries for Skills, Documents, Offers, Applications, Tasks

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <!-- Include AdminLTE CSS and JS files here -->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <!-- Replace with your Navbar code -->
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <!-- Replace with your Main Sidebar Container code -->
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Replace with your Content Header code -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Your Boxes Here -->

                <!-- Box 1: Student's Experiences -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Student's Experiences</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        if ($experiences_result->num_rows > 0) {
                            while($row = $experiences_result->fetch_assoc()) {
                                echo "<p>" . $row["experience_detail"] . "</p>";
                            }
                        } else {
                            echo "<p>No experiences found.</p>";
                        }
                        ?>
                    </div>
                </div>

                <!-- ... similar boxes for Skills, Documents, Offers, Applications, Tasks -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <!-- Replace with your Footer code -->

    <!-- Control Sidebar -->
    <!-- Replace with your Control Sidebar code -->
</div>
<!-- ./wrapper -->

<!-- Include your custom scripts here -->

</body>
</html>
