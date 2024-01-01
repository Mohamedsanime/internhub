<?php
session_start();
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('sidebarstd.php');
// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$role_name = $_SESSION["role_name"];

// Database credentials
$hostname = "localhost";
$username = "root";
$password = "";
$database = "internship";

// Create database connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch user and student data
$query = $mysqli->prepare("SELECT s.*, u.name, u.surname, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
// Check if any row is returned
if ($result->num_rows == 0) {
    echo "No data found for the user.";
   //exit;
}
$student = $result->fetch_assoc();
$nat = mysqli_query($mysqli, 'SELECT num_code, nationality From countries order by nationality');
$countriesQuery = "SELECT * FROM countries";
$countriesResult = $mysqli->query($countriesQuery);
$countries = $countriesResult->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you're updating the 'mobile' and 'address' fields as an example
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $student_id = $_POST['student_id'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $qualification = $_POST['qualification'];
    $address = $_POST['address'];
    $cny = $_POST['cny'];

    // Update query
    $updateQuery = $mysqli->prepare("UPDATE students SET mobile = ?, student_id = ?, gender = ?, qualification =?, cny =?, address = ? WHERE user_id = ?");
    $updateQuery->bind_param("ssssssi", $mobile, $student_id, $gender, $qualification, $cny, $address, $user_id);
    $updateQuery->execute();
    $updateUser = $mysqli->prepare("UPDATE users SET name = ?, surname = ? WHERE id = ?");
    $updateUser->bind_param("ssi", $name, $surname, $user_id);
    $updateUser->execute();

    $response = ['status' => 'success', 'message' => 'Data updated successfully'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
    // Redirect to the same page to show updated data
    //header("Location: studentdata.php");
    //exit();
}

// Close database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <script src="https://kit.fontawesome.com/1f952dc3e7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>
<body>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><b>Student Personal Data</b></h1>
                    </div>
                    
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <form action="studentdata.php" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="studentid" class="form-label">Student ID:</label> 
                                <input type="text" class="form-control" name="student_id" id="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label" for="name">First Name </label>
                                <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($student['name']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label" for="surname">Last Name </label>
                                <input type="text" class="form-control" name="surname" id="surname" value="<?php echo htmlspecialchars($student['surname']); ?>">
                            </div>
                        </div>
                        <div class="col-md-4"> 
                            <div class="form-group">
                                <label class="label" for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($student['email']); ?>" readonly>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                        <label for="gender" class="form-label d-block">Gender: </label>
                                        <input type="radio" name="gender" value="M" <?php echo ($student['gender'] == 'M') ? 'checked' : ''; ?>> Male 
                                        <input type="radio" name="gender" value="M" <?php echo ($student['gender'] == 'F') ? 'checked' : ''; ?>> Female
                                </div>
                        </div>
                        <div class="col-6">
				          <label for="qualification" class="form-label">Qualification: </label> 
				          <input type="text" class="form-control" id="qualification" name="qualification" value="<?php echo htmlspecialchars($student['qualification']); ?>">
			            </div> 
                        <div class="col-6">
                            <div class="form-group">
                                <label for="mobile" class="form-label">Phone No: </label> 
                                <input type="text" class="form-control" id="mobile" name="mobile" pattern="[0-9]{10,13}" value="<?php echo htmlspecialchars($student['mobile']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label for="role">Nationality</label>
                                <select name= "cny" id= "cny" class="form-control" required >
                                <option value="">Select Nationality</option>
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?php echo $country['num_code']; ?>" <?php echo ($student['cny'] == $country['num_code']) ? 'selected' : ''; ?>>
                                            <?php echo $country['en_short_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address:</label>
                            <input type = "textarea" class="form-control" name="address" id="address" rows="3"
                            value="<?php echo htmlspecialchars($student['address']); ?>"></textarea>
                        </div>
                        <input type="submit" value="Update Data">
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
    $(document).ready(function() {
        $("#studentForm").submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: 'studentdata.php', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.status === 'success') {
                        alert('Data updated successfully!');
                    } 
                },
                error: function() {
                    alert('An error occurred while updating.');
                }
            });
        });
    });
    </script>

</body>
</html>
