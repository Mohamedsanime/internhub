<?php
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
//include('sidebarcordinator.php');
// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$role_name = $_SESSION["role_name"];

//echo $user_id;
//echo $username;
//echo $role_name;

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
$query = $mysqli->prepare("SELECT s.*, u.name, u.surname, u.email FROM coordinator s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
 
$coordinator = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $qualification = $_POST['qualification'];
    $address = $_POST['address'];
    $notes = $_POST['notes'];

    // Update query
    $updateQuery = $mysqli->prepare("UPDATE coordinator SET phone = ?, gender = ?, qualification =?, address = ?, notes =? WHERE user_id = ?");
    $updateQuery->bind_param("sssssi", $phone, $gender, $qualification, $address, $notes, $user_id);
    $updateQuery->execute();
    $updateUser = $mysqli->prepare("UPDATE users SET name = ?, surname = ? WHERE id = ?");
    $updateUser->bind_param("ssi", $name, $surname, $user_id);
    $updateUser->execute();

    $response = ['status' => 'success', 'message' => 'Data updated successfully'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();

    // Redirect to the same page to show updated data
    //header("Location: coordinatordata.php");
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
                        <h1 class="m-0"><b>Coordinator Personal Data</b></h1>
                    </div>
                    
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <form id="coordinatorForm" action="coordinatordata.php" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label" for="name">First Name </label>
                                <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($coordinator['name']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label" for="surname">Last Name </label>
                                <input type="text" class="form-control" name="surname" id="surname" value="<?php echo htmlspecialchars($coordinator['surname']); ?>">
                            </div>
                        </div>
                        <div class="col-md-4"> 
                            <div class="form-group">
                                <label class="label" for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($coordinator['email']); ?>" readonly>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                        <label for="gender" class="form-label d-block">Gender: </label>
                                        <input type="radio" name="gender" value="M" <?php echo ($coordinator['gender'] == 'M') ? 'checked' : ''; ?>> Male 
                                        <input type="radio" name="gender" value="M" <?php echo ($coordinator['gender'] == 'F') ? 'checked' : ''; ?>> Female
                                </div>
                        </div>
                        <div class="col-6">
				          <label for="qualification" class="form-label">Qualification: </label> 
				          <input type="text" class="form-control" id="qualification" name="qualification" value="<?php echo htmlspecialchars($coordinator['qualification']); ?>">
			            </div> 
                        <div class="col-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone No: </label> 
                                <input type="text" class="form-control" id="phone" name="phone" pattern="[0-9]{10,13}" value="<?php echo htmlspecialchars($coordinator['phone']); ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address:</label>
                            <input type = "textarea" class="form-control" name="address" id="address" rows="3"
                            value="<?php echo htmlspecialchars($coordinator['address']); ?>"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes:</label>
                            <input type = "textarea" class="form-control" name="notes" id="notes" rows="3"
                            value="<?php echo htmlspecialchars($coordinator['notes']); ?>"></textarea>
                        </div>
                        <input type="submit" value="Update Data">
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
    $(document).ready(function() {
        $("#coordinatorForm").submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: 'coordinatordata.php', 
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
