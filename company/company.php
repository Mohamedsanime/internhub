
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
$comp = $conn->query("SELECT * FROM companies");

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
                                <h1 class="m-0"><b>Companies Data Management</b></h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new_company">
                                        New Company
                                    </button>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
            </div>


            <div class="modal fade" id="new_company" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><b>New Company</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">           
                            <form id="orgForm" action="company_action.php" method="post">
                                <div class="form-row">
                                    <!-- Form fields here -->
                                    <div class="form-group col-md-4">
                                        <label for="org_code">Company Code:</label><br>
                                        <input type="text" id="org_code" name="org_code" class="form-control" required><br>

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
                                        <!-- <label for="org_code">Company Code:</label><br>
                                        <input type="text" id="org_code" name="org_code" required><br>

                                        <label for="name">Name:</label><br>
                                        <input type="text" id="name" name="name" required><br> 

                                        <label for="contactname">Contact Name:</label><br>
                                        <input type="text" id="contactname" name="contactname"><br>

                                        <label for="contactposition">Contact Position:</label><br>
                                        <input type="text" id="contactposition" name="contactposition"><br>

                                        <label for="email">Email:</label><br>
                                        <input type="email" id="email" name="email"><br>

                                        <label for="website">Website:</label><br>
                                        <input type="url" id="website" name="website"><br>

                                        <label for="phone1">Phone 1:</label><br>
                                        <input type="tel" id="phone1" name="phone1"><br>

                                        <label for="phone2">Phone 2:</label><br>
                                        <input type="tel" id="phone2" name="phone2"><br>

                                        <label for="address">Address:</label><br>
                                        <input type="text" id="address" name="address"><br><br> -->

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
                            <div id="tcompany_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="tcompany"
                                            class="table table-bordered table-striped dataTable dtr-inline"
                                            aria-describedby="tcompany_info">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Contact</th>
                                                    <th>Position</th>
                                                    <th>Email</th>
                                                    <th>Website</th>
                                                    <th>Phone 1</th>
                                                    <th>Phone 2</th>
                                                    <th>Address</th>
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
                <h5 class="modal-title" id="editModalLabel"><b>Edit Company Data</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-row">          
                        <div class="form-group col-md-4">
                            <label for="editOrgCode">Company Code:</label>
                            <input type="text" id="editOrgCode" name="org_code" class="form-control" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="editName">Name:</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="editContactName">Contact Name:</label>
                            <input type="text" class="form-control" id="editContactName" name="contactname">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="editContactPosition">Contact Position:</label>
                            <input type="text" class="form-control" id="editContactPosition" name="contactposition">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="editEmail">Email:</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="editWebsite">Website:</label>
                            <input type="url" class="form-control" id="editWebsite" name="website">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="editPhone1">Phone 1:</label>
                            <input type="tel" class="form-control" id="editPhone1" name="phone1">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="editPhone2">Phone 2:</label>
                            <input type="tel" class="form-control" id="editPhone2" name="phone2">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="editAddress">Address:</label>
                            <input type="text" class="form-control" id="editAddress" name="address">
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
                        $('#orgForm')[0].reset();
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

    <script>
        $(document).ready(function() {
            var table = $('#tcompany').DataTable( {
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
                .appendTo( '#tcompany_wrapper .col-md-6:eq(0)' );
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