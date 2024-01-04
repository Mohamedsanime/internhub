<?php
session_start();
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('sidebarcordinator.php');
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
$supQuery = $conn->prepare("SELECT id FROM coordinator WHERE user_id = ?");
$supQuery->bind_param("i", $user_id);
$supQuery->execute();
$supResult = $supQuery->get_result();
if ($supRow = $supResult->fetch_assoc()) {
    $cor_id = $supRow['id'];
} else {
    echo "Coordinator not found.";
    exit;
}

$stdQuery = "SELECT s.id, concat(u.name, ' ',u.surname, ' ==> ', a.description) as student FROM students s 
                JOIN users u ON s.user_id=u.id JOIN application a ON a.student_id = s.id where a.decision2 = 'A' and a.cor_id = $cor_id";
$stdResult = $conn->query($stdQuery);


$sql = "SELECT * FROM cevaluation";
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
                                <h1 class="m-0"><b>Coordinator Evaluation Data Management</b></h1>
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
                                            <label for="quality" class="form-label d-block">Quality of the report: </label>
                                            <input type="radio" name="quality" value="P" > Poor 
                                            <input type="radio" name="quality" value="F" > Fair
                                            <input type="radio" name="quality" value="G" > Good
                                            <input type="radio" name="quality" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="itwork" class="form-label d-block">The student has done IT related work: </label>
                                            <input type="radio" name="itwork" value="P" > Poor 
                                            <input type="radio" name="itwork" value="F" > Fair
                                            <input type="radio" name="itwork" value="G" > Good
                                            <input type="radio" name="itwork" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="knowledge" class="form-label d-block">Knowledge of the student: </label>
                                            <input type="radio" name="knowledge" value="P" > Poor 
                                            <input type="radio" name="knowledge" value="F" > Fair
                                            <input type="radio" name="knowledge" value="G" > Good
                                            <input type="radio" name="knowledge" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="answers" class="form-label d-block">Answering questions: </label>
                                            <input type="radio" name="answers" value="P" > Poor 
                                            <input type="radio" name="answers" value="F" > Fair
                                            <input type="radio" name="answers" value="G" > Good
                                            <input type="radio" name="answers" value="E" > Excellent
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="overall" class="form-label d-block">Overall Evaluation Result: </label>
                                            <input type="radio" name="overall" value="U" > Unsatisfactory 
                                            <input type="radio" name="overall" value="S" > Satisfactory
                                        </div>
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
                                                    <th>Quality</th>
                                                    <th>IT Work</th>                                                 
                                                    <th>Knowledge</th>
                                                    <th>Answers</th>
                                                    <th>Overall</th>
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
                    {targets:[8],searchable:false}
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
                    url: 'cevaluation_actions.php',
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
                url: 'cevaluation_actions.php',
                success: function(data) {
                    $('#dataRows').html(data);
                },
                error: function(xhr, status, error) {
                    console.log("Error occurred: " + status + "\nError: " + error);
                    console.log("Response Text: " + xhr.responseText);
                }
            });
        }


        function editBtn(id, quality, itwork, student, knowledge, answers, overall, comments) {
            $('#id').val(id);
            $('#student').val(student);
            $('input[name="quality"]').prop('checked', false); // Reset first
            $('input[name="quality"][value="' + quality + '"]').prop('checked', true);
            $('input[name="itwork"]').prop('checked', false); // Reset first
            $('input[name="itwork"][value="' + itwork + '"]').prop('checked', true);
            $('input[name="knowledge"]').prop('checked', false); // Reset first
            $('input[name="knowledge"][value="' + knowledge + '"]').prop('checked', true);
            $('input[name="answers"]').prop('checked', false); // Reset first
            $('input[name="answers"][value="' + answers + '"]').prop('checked', true);
            $('input[name="overall"]').prop('checked', false); // Reset first
            $('input[name="overall"][value="' + overall + '"]').prop('checked', true);
            $('#comments').val(comments);
            $('#action').val('Edit');
            $('#modalTitle').text('Edit Evaluation');
            $('#winModal').modal('show');
        }

        function deleteBtn(id) {
            if (confirm('Are you sure you want to delete this Evaluation?')) {
                $.ajax({
                    type: 'POST',
                    url: 'cevaluation_actions.php',
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
