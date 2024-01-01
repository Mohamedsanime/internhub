<?php
session_start();
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('sidebar.php');

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$role_name = $_SESSION["role_name"];


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$conn = new mysqli($servername, $username, $password, $dbname);
$org = mysqli_query($conn, 'SELECT id, name From companies order by name');
$cp = mysqli_query($conn, 'SELECT org_id FROM supervisor WHERE u.user_id = ?');

$query= $conn->prepare("SELECT o.* FROM offers o JOIN companies c ON o.org_id = c.id JOIN supervisor s ON s.org_id = c.id WHERE s.user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$comp = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>companies Management</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet"  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/internhub/admin/assets/dist/css/adminlte.min.css">
    <script src="https://kit.fontawesome.com/1f952dc3e7.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/internhub/admin/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/daterangepicker/daterangepicker.css">

    <!-- Include Bootstrap CSS for styling and modal functionality -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include jQuery for AJAX requests -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="content-wrapper">
            <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0"><b>Internship Offers Data Management</b></h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new_offer">
                                        New Offer
                                    </button>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
            </div>


            <div class="modal fade" id="new_offer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><b>New Offer</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">           
                            <form id="offerForm" action="offer_action.php" method="post">
                                <div class="form-row">
                                    <!-- Form fields here -->
                                    <div class="col-md-6"> 
                                        <div class="form-group">
                                            <label for="role">Company</label>
                                            <select name= "org_id" id= "org_id" class="form-control" required>
                                            <option value="">Select Company</option>
                                                <?php
                                                    while($row=mysqli_fetch_array($org))
                                                    {
                                                ?>
                                                        <option value="<?php echo $row['id']; ?>"> <?php echo $row['name'];?> </option>; 
                                                    <?php } ?>
                                            </select>
                                        </div>
                                    </div>
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

                                        <input type="hidden" id="formAction" name="action" value="Create">
                                        <input type="submit" value="Save">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div id="toffer_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="offerTable"
                                            class="table table-bordered table-striped dataTable dtr-inline"
                                            aria-describedby="toffer_info">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>description</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Location</th>
                                                    <th>Requirement</th>
                                                    <th>Deadline</th>
                                                    <th>Requested</th>
                                                    <th>Accepted</th>
                                                    <th>Notes</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dataRows">
                                                <!-- Data will be loaded here via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"><b>Edit Offers Data</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-row">          
                        <div class="form-group col-md-12">
                            <label for="editDescription">Description:</label><br>
                            <input type="text" id="editDescription" name="description" class="form-control" required><br>

                        </div>
                        <div class="form-group col-md-5">
                            <label for="editFromdate">From Date:</label>
                            <input type="date" name="fromdate" class="form-control" id="editFromdate" >
                        </div>
                        <div class="form-group col-md-5">
                            <label for="editTodate">To Date:</label>
                            <input type="date" name="todate" class="form-control" id="editTodate" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="editLocation">Location:</label>
                             <input type="text" name="location" class="form-control" id="editLocation">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="editRequirementemail">Requirements:</label>
                            <input type="text" name="requirement" class="form-control" id="editRequirement" >
                        </div>
                        <div class="form-group col-md-5">
                            <label for="editAppdeadline">Application Deadline:</label>
                            <input type="date" name="appdeadline" class="form-control" id="editAppdeadline" >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="editRequested">Requested:</label>
                            <input type="text" name="requested" class="form-control" id="editRequested" >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="editFilled">Accepted:</label>
                            <input type="text" name="filled" class="form-control" id="editFilled" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="editNotes">Notes:</label>
                            <input type="textarea" name="notes" class="form-control" id="editNotes" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            loadData();

            $('#offerForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "offer_action.php",
                    data: formData,
                    success: function() {
                        loadData();
                        $('#offerForm')[0].reset();
                    }
                });
            });

            $('#saveEdit').click(function() {
                var formData = $('#editForm').serialize();
                formData += "&action=Update"; 

                $.ajax({
                    type: "POST",
                    url: "offer_action.php",
                    data: formData,
                    success: function(response) {
                        $('#editModal').modal('hide');
                        loadData(); // Reload the data to reflect changes
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                    }
                });
            });


            window.openEditModal = function(description, fromdate, todate, location, requirement, appdeadline, requested, filled, notes) {
                // Populate the edit form fields with the data passed from the table row
                $('#editDescription').val(description);
                $('#editFromdate').val(fromdate);
                $('#editTodate').val(todate);
                $('#editLocation').val(location);
                $('#editRequirement').val(requirement);
                $('#editAppdeadline').val(appdeadline);
                $('#editRequested').val(requested);
                $('#editFilled').val(filled);
                $('#editNotes').val(notes);

                // Open the modal
                $('#editModal').modal('show');
            };

            window.deleteRecord = function(Id) {
                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                    type: "POST",
                    url: "offer_action.php",
                    data: { action: "Delete", id: Id },
                    success: function() {
                        loadData();
                        }
                    });
                }
            };

            $(document).ready(function() {
                loadData();
            });
            
            function loadData() {
                $.ajax({
                    type: "GET",
                    url: "offer_action.php",
                    success: function(data) {
                        $('#dataRows').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                        console.error("Status: " + status);
                        console.error("Response: " + xhr.responseText);
                    }
                });
            }


    });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#offerTable').DataTable( {
                responsive: true,
                lengthChange: false,
                columnDefs: [
                    {targets:[0],visible:false},
                    {targets:[10],searchable:false}
                ],
                autoWidth: false,
                buttons: [ {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },  {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                }, {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }, 'colvis' ]
            } );

            table.buttons().container()
                .appendTo( '#toffer_wrapper .col-md-6:eq(0)' );
        } );

    </script>

       <!-- jQuery -->
       <script src="/internhub/admin/assets/plugins/jquery/jquery.min.js"></script>
       <!-- Include Bootstrap JS for modal functionality -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       <!-- AdminLTE App -->
       <script src="/internhub/admin/assets/dist/js/adminlte.min.js"></script>

<script src="/internhub/admin/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/internhub/admin/assets/plugins/jszip/jszip.min.js"></script>
<script src="/internhub/admin/ssets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/internhub/admin/assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/internhub/admin/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
</body>
</html>