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
$rolesQuery = $conn->query("SELECT id, role_name FROM roles");
$roles = [];
while ($role = $rolesQuery->fetch_assoc()) {
    $roles[] = $role;
}
//print_r($roles);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
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
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <h2>User Management</h2>
                    <!-- Role Filter Dropdown -->
                    <div class="form-group">
                        <label for="roleFilter">Filter by Role:</label>
                        <select class="form-control" id="roleFilter">
                            <option value="">All Roles</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo htmlspecialchars($role['role_name']); ?>">
                                    <?php echo htmlspecialchars($role['role_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <table id="usersTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Email</th>
                                <th>Role Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Database connection setup
                            $conn = new mysqli("localhost", "root", "", "internship");
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT users.id AS usrid, users.name, users.surname, users.email, roles.role_name FROM users
                            INNER JOIN roles  ON (roles.id = users.rol_id)";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    // In the PHP loop that generates the table rows
                                        echo "<tr>
                                        <td>{$row['usrid']}</td>
                                        <td>{$row['name']}</td>
                                        <td>{$row['surname']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['role_name']}</td>
                                        <td><button class='btn' onclick='editUser(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button></td>
                                        
                                        </tr>";
                                }
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm">
                            <input type="hidden" id="editUserId" name="id">
                            <div class="form-group">
                                <label for="editUserName">Name</label>
                                <input type="text" class="form-control" id="editUserName" name="name">
                            </div>
                            <div class="form-group">
                                <label for="editUserSurname">Surname</label>
                                <input type="text" class="form-control" id="editUserSurname" name="surname">
                            </div>
                            <div class="form-group">
                                <label for="editUserEmail">Email</label>
                                <input type="email" class="form-control" id="editUserEmail" name="email">
                            </div>
                            <div class="form-group">
                                <label for="editUserRole">Role</label>
                                <select class="form-control" id="editUserRole" name="rol_id">
                                    <?php foreach ($roles as $wrole): ?>
                                    <option value="<?php echo $wrole['id']; ?>">
                                        <?php echo htmlspecialchars($wrole['role_name']); ?>
                                    </option>
                                        <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                    <!-- Edit User Modal -->
                    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"><!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editUserForm">
                                            <div class="form-group">
                                                <label for="editUserId">User ID</label>
                                                <input type="text" class="form-control" id="editUserId" name="id" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="editUserName">Name</label>
                                                <input type="text" class="form-control" id="editUserName" name="name">
                                            </div>
                                            <div class="form-group">
                                                <label for="editUserSurname">Surname</label>
                                                <input type="text" class="form-control" id="editUserSurname" name="surname">
                                            </div>
                                            <div class="form-group">
                                                <label for="editUserEmail">Email</label>
                                                <input type="email" class="form-control" id="editUserEmail" name="email">
                                            </div>

                                            <div class="form-group">
                                                <label for="editUserRole">Role</label>
                                                <select class="form-control" id="editUserRole" name="rol_id">
                                                    <?php foreach ($roles as $wrole): ?>
                                                        <option value="<?php echo $wrole['id']; ?>">
                                                            <?php echo htmlspecialchars($wrole['role_name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Include necessary JavaScript files for jQuery, Bootstrap, AdminLTE, DataTables -->
    <script>

        $(document).ready(function() {
                var table = $('#usersTable').DataTable({
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-4"your-custom-element><"col-sm-12 col-md-2"f>>tip'
    });

                // Role filter change event
                $('#roleFilter').on('change', function() {
                    var selectedRoleName = $(this).val();
                    console.log("Filtering by role:", selectedRoleName); // Debugging
                    table.column(4).search(selectedRoleName).draw();
                });
            });
            $(document).ready(function() {
    var table = $('#usersTable').DataTable({
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-4"your-custom-element><"col-sm-12 col-md-2"f>>tip'
    });
    // Move your dropdown into the custom element
    $("your-custom-element").append($("#roleFilter"));
});


            // Edit user function
            window.editUser = function(userData) {
                $('#editUserId').val(userData.id);
                $('#editUserName').val(userData.name);
                $('#editUserSurname').val(userData.surname);
                $('#editUserEmail').val(userData.email);
                $('#editUserRole').val(userData.rol_id); // This will select the correct role
                console.log("Setting role ID to: ", userData.rol_id);
                $('#editUserModal').modal('show');
            };

            // AJAX request to update user data
            $('#editUserForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'update_users.php', // A separate PHP file to handle the update
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editUserModal').modal('hide');
                        location.reload(); // Reload the page to see the changes
                    }
                });
            });
        
    </script>
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
