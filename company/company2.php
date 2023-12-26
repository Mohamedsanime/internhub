<?php
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('../admin/includes/sidebar.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'internship';

$db = new mysqli($host, $username, $password, $dbname);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch companies
$comp = $db->query("SELECT * FROM organization");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Companies Data Management</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/internhub/admin/assets/dist/css/adminlte.min.css">
    <script src="https://kit.fontawesome.com/1f952dc3e7.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/internhub/admin/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>
<body>
    <div class="content-wrapper">
 

            <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0"><b>Companies Data Management</b></h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new_role">
                                        New Company
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
                            <h5 class="modal-title" id="exampleModalLabel"><b>New Company</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="../ajax/ogrenci_kayit.php" method="post" id="personel_kaydet">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="org_code">Code:</label>
                                        <input type="text" name="org_code" class="form-control" id="org_code" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="name">Company Name:</label>
                                        <input type="text" name="name" class="form-control" id="name">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="contactname">Contact Name:</label>
                                        <input type="text" name="contactname" class="form-control" id="contactname" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="contactposition">Contact Position:</label>
                                        <input type="text" name="contactposition" class="form-control" id="contactposition">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" class="form-control" id="email" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="website">Website:</label>
                                        <input type="url" name="website" class="form-control" id="website" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone1">Phone 1:</label>
                                        <input type="tel" name="phone1" class="form-control" id="phone1" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone2">Phone 2:</label>
                                        <input type="tel" name="phone2" class="form-control" id="phone2" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="address">Address:</label>
                                        <input type="textarea" name="address" class="form-control" id="address" >
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="kaydet">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Roles Table 
            <table>
                <tr>
                    <th>ID</th>
                    <th>Role Name</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $comp->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['role_name']; ?></td>
                        <td>
                            
                            <form method="post">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="role_name" value="<?php echo $row['role_name']; ?>">
                                <input type="submit" name="update" value="Update">
                            </form>
                          
                            <form method="post">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="delete" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table> -->

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
                                            class="table table-bordered table-striped dataTable dtr-inline"
                                            aria-describedby="example1_info">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Code</th>
                                                    <th>Company</th>
                                                    <th>Contact</th>
                                                    <th>Position</th>
                                                    <th>Email</th>
                                                    <th>website</th>
                                                    <th>Phone 1</th>
                                                    <th>Phone 2</th>
                                                    <th>Address</th>
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


         <!-- /.content-wrapper -->

    </div>

    <!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Organization</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="org_code">Code:</label>
                            <input type="text" name="org_code" class="form-control" id="org_code" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="name">Company Name:</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="contactname">Contact Name:</label>
                            <input type="text" name="contactname" class="form-control" id="contactname" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="contactposition">Contact Position:</label>
                            <input type="text" name="contactposition" class="form-control" id="contactposition">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" id="email" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="website">Website:</label>
                            <input type="url" name="website" class="form-control" id="website" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone1">Phone 1:</label>
                            <input type="tel" name="phone1" class="form-control" id="phone1" >
                         </div>
                        <div class="form-group col-md-6">
                            <label for="phone2">Phone 2:</label>
                             <input type="tel" name="phone2" class="form-control" id="phone2" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address">Address:</label>
                            <input type="textarea" name="address" class="form-control" id="address" >
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

            $('#orgForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "company_action.php",
                    data: formData,
                    success: function() {
                        loadData();
                    }
                });
            });

            $('#saveEdit').click(function() {
                var formData = $('#editForm').serialize();
                formData += "&action=Update"; // Add the action parameter to your formData

                $.ajax({
                    type: "POST",
                    url: "company_action.php",
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
                    url: "company_action.php",
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
                    url: "company_action.php",
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

     <!-- jQuery -->
    <script src="/internhub/admin/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/internhub/admin/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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

    <script>

        $(document).ready(function() {
            var table = $('#example1').DataTable( {
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
                .appendTo( '#example1_wrapper .col-md-6:eq(0)' );
        } );
       

        $("#edit2").click(function () {
            $("#personel_kaydet").submit();
            
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
$db->close();
?>
