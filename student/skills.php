<?php
session_start();
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
//include('sidebarstd.php');
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

$stdQuery = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stdQuery->bind_param("i", $user_id);
$stdQuery->execute();
$stdResult = $stdQuery->get_result();
if ($stdRow = $stdResult->fetch_assoc()) {
    $student_id = $orgRow['id'];
} else {
    echo "Student not found.";
    exit;
}

// Fetch skills for the logged-in student
$query = $conn->prepare("SELECT * FROM skills WHERE student_id = ?");
$query->bind_param("i", $student_id);
$query->execute();
$result = $query->get_result();

// Include AdminLTE and necessary CSS/JS files
?>
<!DOCTYPE html>
<html>
<head>
    <title>Skills Management</title>
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
                        <h1 class="m-0"><b>Student Skills Data Management</b></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <button data-toggle="modal" data-target="#skillModal" onclick="clearModal()">Add New Skill</button>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Skills Table -->
        <table id="skillsTable" class="table table-bordered table-striped dataTable dtr-inline">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Level</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['level']; ?></td>
                        <td><?php echo $row['notes']; ?></td>
                        <td>
                            <button onclick="editSkill(<?php echo $row['id']; ?>)">Edit</button>
                            <button onclick="deleteSkill(<?php echo $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Skills Modal -->
        <div class="modal fade" id="skillModal" tabindex="-1" role="dialog" aria-labelledby="skillModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="skillModalLabel">Add/Edit Skill</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="skillForm" action="skills_action.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="skillId" name="id">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="form-group">
                                <label for="level">Level</label>
                                <input type="text" class="form-control" id="level" name="level" required>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Skill</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        <input type="submit" value="Save Offer">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#skillForm").submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: 'skills_action.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#skillModal').modal('hide');
                        // Reload the page or update the skills table to reflect changes
                        location.reload();
                    },
                    error: function() {
                        alert('An error occurred.');
                    }
                });
            });
        });

        function prepareAddSkill() {
            $('#skillId').val('');
            $('#description').val('');
            $('#level').val('');
            $('#notes').val('');
            $('#skillModalLabel').text('Add Skill');
        }

        function editSkill(id) {
            // Fetch skill data and populate the modal for editing
            $.ajax({
                url: 'skills_action.php',
                type: 'POST',
                data: { action: 'fetch', id: id },
                success: function(skill) {
                    $('#skillId').val(skill.id);
                    $('#description').val(skill.description);
                    $('#level').val(skill.level);
                    $('#notes').val(skill.notes);
                    $('#skillModalLabel').text('Edit Skill');
                    $('#skillModal').modal('show');
                }
            });
        }
  
        // JavaScript functions for handling edit, delete, and modal
        function deleteSkill(id) {
            if(confirm("Are you sure you want to delete this skill?")) {
                $.ajax({
                    url: 'skills_action.php',
                    type: 'POST',
                    data: { action: 'delete', id: id },
                    success: function(response) {
                        if(response.status === 'success') {
                            alert('Skill deleted successfully!');
                            location.reload(); // Reload the page to see the changes
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting.');
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
