<?php
session_start();
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('sidebar.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$connname = 'internship';

$conn = new mysqli($host, $username, $password, $connname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["id"];
$supQuery = $conn->prepare("SELECT id FROM supervisor WHERE user_id = ?");
$supQuery->bind_param("i", $user_id);
$supQuery->execute();
$supResult = $supQuery->get_result();
if ($supRow = $supResult->fetch_assoc()) {
    $sup_id = $supRow['id'];
} else {
    echo "Supervisor not found.";
    exit;
}

$stdQuery = "SELECT s.id, concat(u.name, ' ',u.surname, ' ==> ', a.description) as student FROM students s 
                JOIN users u ON s.user_id=u.id JOIN application a ON a.student_id = s.id where a.decision = 'A' and a.sup_id = $sup_id";
$stdResult = $conn->query($stdQuery);


$sql = "SELECT * FROM sevaluation";
$result = $conn->query($sql);
$seval = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Evaluations Data Management</title>

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
                                <h1 class="m-0"><b>Supervisor Evaluation Data Management</b></h1>
                            </div><!-- /.col -->
                             <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button id="addBtn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#winModal">
                                        New Evaluation
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
                            <h5 class="modal-title" id="modalTitle">Add Evaluation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="winForm">
                                <div class="form-row">  
                                    <input type="hidden" id="id" name="id">
                                    <div class="col-md-10"> 
                                        <div class="form-group">
                                            <label for="student_id">Students </label>
                                            <select name="student_id" id="student_id" class="form-control" required ">
                                                <option value="">Select a Student</option>
                                                <?php
                                                    while ($std = $stdResult->fetch_assoc()) {
                                                        echo "<option value='" . $std['id'] . "'>" 
                                                            . htmlspecialchars($std['student']) . 
                                                            "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="interest" class="form-label d-block">Interest: </label>
                                            <input type="radio" name="interest" value="P" > Poor 
                                            <input type="radio" name="interest" value="F" > Fair
                                            <input type="radio" name="interest" value="G" > Good
                                            <input type="radio" name="interest" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="attendance" class="form-label d-block">Attendance: </label>
                                            <input type="radio" name="attendance" value="P" > Poor 
                                            <input type="radio" name="attendance" value="F" > Fair
                                            <input type="radio" name="attendance" value="G" > Good
                                            <input type="radio" name="attendance" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="technical" class="form-label d-block">Technical Knowledge and Ability: </label>
                                            <input type="radio" name="technical" value="P" > Poor 
                                            <input type="radio" name="technical" value="F" > Fair
                                            <input type="radio" name="technical" value="G" > Good
                                            <input type="radio" name="technical" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="general" class="form-label d-block">General Behavior: </label>
                                            <input type="radio" name="general" value="P" > Poor 
                                            <input type="radio" name="general" value="F" > Fair
                                            <input type="radio" name="general" value="G" > Good
                                            <input type="radio" name="general" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="overall" class="form-label d-block">Overall Evaluation Result: </label>
                                            <input type="radio" name="overall" value="P" > Poor 
                                            <input type="radio" name="overall" value="F" > Fair
                                            <input type="radio" name="overall" value="G" > Good
                                            <input type="radio" name="overall" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="summary">Summary of the Work Done</label>
                                        <input type="textarea" class="form-control" id="summary" name="summary" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="comments">General Comments</label>
                                        <input type="textarea" class="form-control" id="comments" name="comments" >
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
                                                    <th>Interest</th>
                                                    <th>Attendance</th>                                                 
                                                    <th>Technical</th>
                                                    <th>General</th>
                                                    <th>Overall</th>
                                                    <th>Summary</th>
                                                    <th>comments</th>
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
        function fillstdDetails() {
            var stdSelect = document.getElementById('student_id');
            var selectedOption = stdSelect.options[stdSelect.selectedIndex];
        }
    </script>
    <script>

        $(document).ready(function() {
            var table = $('#example1').DataTable( {
                responsive: true,
                lengthChange: false,
                columnDefs: [
                    {targets:[0],visible:false},
                    {targets:[9],searchable:false}
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
                $('#modalTitle').text('Add Evaluation');
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
                    url: 'sevaluation_action.php',
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
                url: 'sevaluation_actions.php',
                success: function(data) {
                    $('#dataRows').html(data);
                },
                error: function(xhr, status, error) {
                    console.log("Error occurred: " + status + "\nError: " + error);
                    console.log("Response Text: " + xhr.responseText);
                }
            });
        }


        function editBtn(id, interest, attendance, student_id, technical, general, overall, summary, comments) {
            $('#id').val(id);
            $('#student_id').val(student_id);
           // $('#interest').val(interest);
            $('input[name="interest"]').prop('checked', false); // Reset first
            $('input[name="interest"][value="' + interest + '"]').prop('checked', true);
            $('input[name="attendance"]').prop('checked', false); // Reset first
            $('input[name="attendance"][value="' + attendance + '"]').prop('checked', true);
            $('input[name="technical"]').prop('checked', false); // Reset first
            $('input[name="technical"][value="' + technical + '"]').prop('checked', true);
            $('input[name="general"]').prop('checked', false); // Reset first
            $('input[name="general"][value="' + general + '"]').prop('checked', true);
            $('input[name="overall"]').prop('checked', false); // Reset first
            $('input[name="overall"][value="' + overall + '"]').prop('checked', true);
           // $('#attendance').val(attendance);
          // $('#technical').val(technical);
           // $('#general').val(general);
            //$('#overall').val(overall);
            $('#summary').val(summary);
            $('#comments').val(comments);
            $('#action').val('Edit');
            $('#modalTitle').text('Edit Evaluation');
            $('#winModal').modal('show');
        }

        function deleteBtn(id) {
            if (confirm('Are you sure you want to delete this Evaluation?')) {
                $.ajax({
                    type: 'POST',
                    url: 'sevaluation_actions.php',
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
