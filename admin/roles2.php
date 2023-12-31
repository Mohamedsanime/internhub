<?php
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('../admin/includes/sidebar.php');
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$roles = $conn->query("SELECT id, role_name FROM roles");

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
                                <h1 class="m-0"><b>System Roles Management</b></h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new_role">
                                    New System Role
                                    </button>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
            </div>


            <div class="modal fade" id="new_role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><b> New System Role</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">           
                            <form id="rolesForm" action="roles_action.php" method="post">
                                <div class="form-row">
                                    <!-- Form fields here -->
                                    <div class="form-group col-md-4">
                                        <label for="org_code">Role Name:</label><br>
                                        <input type="text" id="role_name" name="role_name" class="form-control" required><br>

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
                            <div id="trole_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="trole"
                                            class="table table-bordered table-striped dataTable dtr-inline"
                                            aria-describedby="trole_info">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Role Name</th>
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
                <h5 class="modal-title" id="editModalLabel"><b>Edit Roles Data</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-row">          
                        <div class="form-group col-md-4">
                            <label for="editRoleName">Role Name:</label>
                            <input type="text" id="editRoleName" name="role_name" class="form-control" >
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

            $('#roleForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "roles_action.php",
                    data: formData,
                    success: function() {
                        loadData();
                        $('#roleForm')[0].reset();
                    }
                });
            });

            $('#saveEdit').click(function() {
                var formData = $('#editForm').serialize();
                formData += "&action=Update"; // Add the action parameter to your formData

                $.ajax({
                    type: "POST",
                    url: "roles_action.php",
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


            window.openEditModal = function(roleName) {
                // Populate the edit form fields with the data passed from the table row
                $('#editRoleName').val(roleName);

                // Open the modal
                $('#editModal').modal('show');
            };

            window.deleteRecord = function(roleName) {
                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                    type: "POST",
                    url: "roles_action.php",
                    data: { action: "Delete", role_name: roleName },
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
                    url: "roles_action.php",
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
            var table = $('#trole').DataTable( {
                responsive: true,
                lengthChange: false,
                columnDefs: [
                    {targets:[0],visible:false},
                    {targets:[2],searchable:false}
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
                .appendTo( '#trole_wrapper .col-md-6:eq(0)' );
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