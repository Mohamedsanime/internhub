<?php
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'internship';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch roles
$roles = $conn->query("SELECT * FROM roles");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Roles Management</title>

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

            <!-- Create Role Form -->
            <div class="modal fade" id="new_role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><b>New System Role</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="roles.php" method="post" id="role_form">
                                <div class="form-row">
                                    <div class="form-group col-md-11">
                                        <label for="inputEmail4">Role Name:</label>
                                        <input type="text" name="ad" class="form-control" id="inputEmail4" placeholder="Role name">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                           
                            <input type="hidden" id="formAction" name="action" value="Create">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-primary" id="save">Save</button> -->
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
                                        <table id="example1"
                                            class="table table-bordered table-striped dataTable dtr-inline table-hover"
                                            aria-describedby="example1_info">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Role Name</th>
                                                    <th>Action</th>
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

                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>

               <!-- Control Sidebar -->
               <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
         <!-- /.content-wrapper -->

    </div>
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

    <script>
        $(document).ready(function() {
            loadData();

            $('#role_form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "roles_action.php",
                    data: formData,
                    success: function() {
                        loadData();
                        $('#role_form')[0].reset();
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


            window.openEditModal = function(orgCode, name, contactName, contactPosition, email, website, phone1, phone2, address) {
                // Populate the edit form fields with the data passed from the table row
                $('#editOrgCode').val(orgCode);
                $('#editName').val(name);
                $('#editContactName').val(contactName);
                $('#editContactPosition').val(contactPosition);
                $('#editEmail').val(email);
                $('#editWebsite').val(website);
                $('#editPhone1').val(phone1);
                $('#editPhone2').val(phone2);
                //var address = JSON.parse('"' + encodedAddress + '"');
                $('#editAddress').val(address);

                // Open the modal
                $('#editModal').modal('show');
            };

            window.deleteRecord = function(orgCode) {
                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                    type: "POST",
                    url: "roles_action.php",
                    data: { action: "Delete", org_code: orgCode },
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
            var table = $('#example1').DataTable( {
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
                .appendTo( '#example1_wrapper .col-md-6:eq(0)' );
        } );
       

        $("#edit2").click(function () {
            $("#role_form").submit();
            
        });
        $("#bolum").change(function () {
            let bolum_id = $(this).val();
          $.ajax({
            type : 'POST',
            url : '../ajax/form_data.php',
            data:{
                bolum_id:bolum_id
            },
            success:function(data) {
                $("#danisman").html(data);
                console.log(data);
            }
          })
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
