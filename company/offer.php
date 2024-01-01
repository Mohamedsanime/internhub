<?php
session_start();
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('sidebar.php');
// Include database connection

//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$conn = new mysqli($servername, $username, $password, $dbname);
$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$role_name = $_SESSION["role_name"];

if (!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch the supervisor's organization ID
$orgQuery = $conn->prepare("SELECT org_id FROM supervisor WHERE user_id = ?");
$orgQuery->bind_param("i", $user_id);
$orgQuery->execute();
$orgResult = $orgQuery->get_result();
if ($orgRow = $orgResult->fetch_assoc()) {
    $org_id = $orgRow['org_id'];
} else {
    echo "Organization not found for the supervisor.";
    exit;
}

// Fetch offers from the database for the supervisor's organization
$query = "SELECT * FROM offers WHERE org_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $org_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Offers</title>
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
                                <h1 class="m-0"><b>Internship Offers Data Management</b></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                <button data-toggle="modal" data-target="#offerModal" onclick="clearModal()">Add New Offer</button>
                                </ol>
                            </div>
                        </div>
                    </div>
            </div>

        <!-- Table of Offers -->
        <table class="table table-bordered table-striped dataTable dtr-inline">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Location</th>
                    <th>Requirement</th>
                    <th>Application Deadline</th>
                    <th>Requested</th>
                    <th>Filled</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['fromdate']; ?></td>
                        <td><?php echo $row['todate']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td><?php echo $row['requirement']; ?></td>
                        <td><?php echo $row['appdeadline']; ?></td>
                        <td><?php echo $row['requested']; ?></td>
                        <td><?php echo $row['filled']; ?></td>
                        <td><?php echo $row['notes']; ?></td>
                        <td>
                            <button onclick="editOffer(<?php echo htmlspecialchars(json_encode($row)); ?>)"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteOffer(<?php echo $row['id']; ?>)"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add/Edit Offer Modal -->
        <div id="offerModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-body"> 
                    <form id="offerForm" action="offer_actions.php" method="post">
                        <div class="form-row">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="id" id="offerId">
                        
                            <div class="form-group col-md-12">
                                        <label for="description">Description:</label><br>
                                        <input type="text" id="description" name="description" class="form-control" required><br>

                            </div>
                            <div class="form-group col-md-5">
                                        <label for="fromdate">From Date:</label>
                                        <input type="date" name="fromdate" class="form-control" id="fromdate" >
                            </div>
                            <div class="form-group col-md-5">
                                        <label for="todate">To Date:</label>
                                        <input type="date" name="todate" class="form-control" id="todate" >
                            </div>
                            <div class="form-group col-md-12">
                                        <label for="location">Location:</label>
                                        <input type="text" name="location" class="form-control" id="location">
                            </div>
                            <div class="form-group col-md-12">
                                        <label for="requirementemail">Requirements:</label>
                                        <input type="text" name="requirement" class="form-control" id="requirement" >
                            </div>
                            <div class="form-group col-md-5">
                                        <label for="appdeadline">Application Deadline:</label>
                                        <input type="date" name="appdeadline" class="form-control" id="appdeadline" >
                            </div>
                            <div class="form-group col-md-3">
                                        <label for="requested">Requested:</label>
                                        <input type="text" name="requested" class="form-control" id="requested" >
                            </div>
                            <div class="form-group col-md-3">
                                        <label for="filled">Accepted:</label>
                                        <input type="text" name="filled" class="form-control" id="filled" >
                            </div>
                            <div class="form-group col-md-12">
                                        <label for="notes">Notes:</label>
                                        <input type="textarea" name="notes" class="form-control" id="notes" >
                            </div>

                        </div>

                        <input type="submit" value="Save Offer">
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editOffer(offer) {
            // Populate the form with data from the offer
            document.getElementById('offerId').value = offer.id;
            document.getElementById('description').value = offer.description;
            document.getElementById('fromdate').value = offer.fromdate
            document.getElementById('todate').value = offer.todate
            document.getElementById('location').value = offer.location
            document.getElementById('requirement').value = offer.requirement
            document.getElementById('appdeadline').value = offer.appdeadline
            document.getElementById('requested').value = offer.requested
            document.getElementById('filled').value = offer.filled
            document.getElementById('notes').value = offer.notes
            // Set the action to 'edit'
            document.getElementById('offerForm').action.value = 'edit';
            // Open the modal
            $('#offerModal').modal('show');
        }

        function deleteOffer(id) {
            if(confirm('Are you sure you want to delete this offer?')) {
                window.location.href = 'offer_actions.php?action=delete&id=' + id;
            }
        }

        function clearModal() {
            // Clear all fields in the modal
            document.getElementById('offerForm').reset();
            // Set the action to 'add'
            document.getElementById('offerForm').action.value = 'add';
        }
    </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- jQuery -->
        <script src="assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/dist/js/adminlte.min.js"></script>

    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/plugins/jszip/jszip.min.js"></script>
    <script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
</body>
</html>
