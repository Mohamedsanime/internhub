<?php
session_start();
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('sidebarstd.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$connname = 'internship';

$conn = new mysqli($host, $username, $password, $connname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function uploadFile($file) {
    $targetDir = "C:/xampp/htdocs/uploads/";
    $filePath = $targetDir . basename($file["name"]);

    // File upload logic
    if (move_uploaded_file($file["tmp_name"], $filePath)) {
        return "File uploaded successfully.";
    } else {
        return "Error in file upload.";
    }
}

// Check if the call is an AJAX request
if (!empty($_FILES)) {
    echo uploadFile($_FILES['file']);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Insurance Form Data Management</title>

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
    <script>
        $(document).ready(function() {
            $('#uploadButton').click(function() {
                var formData = new FormData();
                formData.append('file', $('#fileToUpload')[0].files[0]);

                $.ajax({
                    url: 'stddocuments.php', // Your PHP script
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="content-wrapper">
 

            <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0"><b>Insurance Form Data Management</b></h1>
                            </div>
                              <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button id="addBtn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#winModal">
                                        New Form
                                    </button>
                                </ol>
                            </div> 
                        </div>
                    </div>
            </div>
            
            <div class="modal fade" id="winModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add Document</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="winForm">
                                <div class="form-row">  
                                    <input type="hidden" id="id" name="id">
                                    <!-- <div class="form-group col-md-10">
                                        <label for="student_id">Student</label>
                                        <input type="text" class="form-control" id="student_id" name="student_id" required>
                                    </div> -->
                                    <div class="form-group col-md-6">
                                        <label for="submiton">Submitted On</label>
                                        <input type="date" class="form-control" id="submiton" name="submiton" required>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                                    <label for="decision" class="form-label d-block">Decision: </label>
                                                    <input type="radio" name="decision" value="A" > Accepted 
                                                    <input type="radio" name="decision" value="R" > Rejected
                                            </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="decisiondate">Decision Date</label>
                                        <input type="date" class="form-control" id="decisiondate" name="decisiondate" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="notes">Notes</label>
                                        <input type="textarea" class="form-control" id="notes" name="notes" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="notes">Select file to upload</label>
                                       <!-- <input type="file" name="fileToUpload" id="fileToUpload">
                                        <input type="submit" value="Upload File" name="submit"> -->
                                        <input type="file" id="fileToUpload">
                                        <button id="uploadButton">Upload</button>
                                    </div>
                                    <input type="hidden" id="action" name="action" value="Add">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
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
                                        <table id="example1"
                                            class="table table-bordered table-striped dataTable dtr-inline"
                                            aria-describedby="example1_info">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Student</th>
                                                    <th>Submit On</th>
                                                    <th>Decision</th>                                                 
                                                    <th>Notes</th>
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
                    </div>
                </div>
            </div>
        </div>


         <!-- /.content-wrapper -->

    </div>
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
    <script src="/internhub/admin/assets/plugins/pdfmake/pdfmake.min.js"></script>
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
                    {targets:[6],searchable:false}
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
            loadData();
            
            // Open modal in add mode
            $('#addBtn').click(function() {
                $('#winForm')[0].reset();
                $('#action').val('Add');
                $('#modalTitle').text('Add Form');
                $('#id').val('');
            });

            // Form submission
            $('#winModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();  // Remove any stray backdrops
                });
            $('#winForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'insurform_actions.php',
                    data: formData,
                    success: function() {
                        $('#winModal').modal('hide');                       
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
                url: 'insurform_actions.php',
                success: function(data) {
                    $('#dataRows').html(data);
                }
            });
        }

        function editBtn(id, submiton, decision, decisiondate, student_id, notes) {
            $('#id').val(id);
            $('#student_id').val(student_id);
            $('#submiton').val(submiton);
            $('#decision').val(decision);
            $('#decisiondate').val(decisiondate);
            $('#notes').val(notes);
            $('#action').val('Edit');
            $('#modalTitle').text('Edit Form');
            $('#winModal').modal('show');
        }

        function deleteBtn(id) {
            if (confirm('Are you sure you want to delete this Form?')) {
                $.ajax({
                    type: 'POST',
                    url: 'insurform_actions.php',
                    data: { action: 'Delete', id: id },
                    success: function(response) {
                        loadData();
                    }
                });
            }
        }

    </script>
</body>
</html>

<?php
$conn->close();
?>
