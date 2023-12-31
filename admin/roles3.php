<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Role Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>

</head>
<body>
    <div class="container mt-5">
        <h2>Roles Management</h2>
        <button id="addRoleBtn" class="btn btn-primary mb-3" data-toggle="modal" data-target="#roleModal">Add New Role</button>
        <table id="rolesTable" class="table table-bordered table-hover">
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

    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                responsive: true,
                lengthChange: false,
                columnDefs: [
                    {targets:[0],visible:false},
                    {targets:[2],searchable:false}
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                ],
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
            $('#roleForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'roles_action3.php',
                    data: formData,
                    success: function(response) {
                        $('#roleModal').modal('hide');
                        loadData();
                    }
                });
            });
        });

        function loadData() {
            $.ajax({
                type: 'GET',
                url: 'roles_action3.php',
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
                    url: 'roles_action3.php',
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
