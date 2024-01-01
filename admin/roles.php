<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'internship';

$db = new mysqli($host, $username, $password, $dbname);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch roles
$roles = $db->query("SELECT id, role_name FROM roles");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Role Management</title>
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
                        <h1 class="m-0"><b>System Roles Management</b></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <button id="addRoleBtn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#roleModal"> New System Role </button>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="roleForm">
                                <input type="hidden" id="roleId" name="id">
                                <div class="form-group">
                                    <label for="roleName">Role Name</label>
                                    <input type="text" class="form-control" id="roleName" name="role_name" required>
                                </div>
                                <input type="hidden" id="action" name="action" value="Add">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
             <!-- Main content -->
             <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                    <table id="rolesTable" class="table table-bordered table-striped dataTable dtr-inline">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
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
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                responsive: true,
                lengthChange: false,
                columnDefs: [
                    {targets:0,visible:false},
                    {targets:[2],searchable:false}
                ],
                autoWidth: false,
                dom: 'Bfrtip',
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
            });
            loadData();
            
            // Open modal in add mode
            $('#addRoleBtn').click(function() {
                $('#roleForm')[0].reset();
                $('#action').val('Add');
                $('#modalTitle').text('Add Role');
                $('#roleId').val('');
            });

            // Form submission
            $('#roleModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();  // Remove any stray backdrops
                });
            $('#roleForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'roles_action.php',
                    data: formData,
                    success: function() {
                        $('#roleModal').modal('hide');                       
                        $('body').removeClass('modal-open');
                        loadData();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error: ", textStatus, errorThrown);
                    }
                });
            });
        });

        
        function loadData() {
            $.ajax({
                type: 'GET',
                url: 'roles_action.php',
                success: function(data) {
                    $('#dataRows').html(data);
                }
            });
        }

        function editRole(id, name) {
            $('#roleId').val(id);
            $('#roleName').val(name);
            $('#action').val('Edit');
            $('#modalTitle').text('Edit Role');
            $('#roleModal').modal('show');
        }

        function deleteRole(id) {
            if (confirm('Are you sure you want to delete this role?')) {
                $.ajax({
                    type: 'POST',
                    url: 'roles_action.php',
                    data: { action: 'Delete', id: id },
                    success: function(response) {
                        loadData();
                    }
                });
            }
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
