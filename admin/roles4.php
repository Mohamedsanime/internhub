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
$roles = $conn->query("SELECT id, role_name FROM roles");
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Roles Management</title>
    <!-- Include other necessary AdminLTE CSS and JS -->
    <script language="JavaScript" type="text/javascript" src="/js/jquery-1.2.6.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery-ui-personalized-1.5.2.packed.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/sprinkle.js"></script>
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
</head>
<body>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- ... -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <h2>Roles Management</h2>
                <button id="addRoleBtn" class="btn btn-primary mb-3" data-toggle="modal" data-target="#roleModal">New System Role</button>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $roles->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['role_name']; ?></td>
                            <td>
                                <button class="btn btn-warning" onclick="editRole(<?php echo $row['id']; ?>, '<?php echo $row['role_name']; ?>')">Edit</button>
                                <button class="btn btn-danger" onclick="deleteRole(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Role Modal -->
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
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('#addRoleBtn').click(function() {
                $('#roleForm')[0].reset();
                $('#action').val('Add');
                $('#modalTitle').text('Add Role');
                $('#roleId').val('');
            });

            $('#roleForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'rolesaction.php',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        $('#roleModal').modal('hide');
                        location.reload(); // Reload the page to update the table
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error: ", textStatus, errorThrown); // Log any error
                    }
                });
            });
        });

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
                    url: 'rolesaction.php',
                    data: { action: 'Delete', id: id },
                    success: function(response) {
                        location.reload(); // Reload the page to update the table
                    }
                });
            }
        }
    </script>
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
<?php
$conn->close();
include('includes/footer.php');
?>
