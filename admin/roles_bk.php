<?php
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'internship';

$db = new mysqli($host, $username, $password, $dbname);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $roleName = $db->real_escape_string($_POST['role_name']);
        $db->query("INSERT INTO roles (role_name) VALUES ('{$roleName}')");
    } elseif (isset($_POST['update'])) {
        $id = $db->real_escape_string($_POST['id']);
        $roleName = $db->real_escape_string($_POST['role_name']);
        $db->query("UPDATE roles SET role_name = '{$roleName}' WHERE id = {$id}");
    } elseif (isset($_POST['delete'])) {
        $id = $db->real_escape_string($_POST['id']);
        $db->query("DELETE FROM roles WHERE id = {$id}");
    }
}

// Fetch roles
$roles = $db->query("SELECT id, role_name FROM roles");
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Roles Management</title>

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
                            <form action="../ajax/ogrenci_kayit.php" method="post" id="personel_kaydet">
                                <div class="form-row">
                                    <div class="form-group col-md-11">
                                        <label for="inputEmail4">Role Name:</label>
                                        <input type="text" name="ad" class="form-control" id="inputEmail4" placeholder="Role name">
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
                <?php while ($row = $roles->fetch_assoc()): ?>
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
                                                    <th>Role Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $query=$db->query("SELECT id, role_name FROM roles ");
                                            $vrow = $query->fetch_all(MYSQLI_ASSOC);
                                            //$query = "SELECT * FROM tbl_comment WHERE parent_comment_id = :parent_id";
                                           
                                            // $statement->bindParam(':parent_id', $parent_id);

                                            ?>

                                            <?php foreach ($vrow as $roles): ?>
                                                <tr>
                                                    <td><?php echo $roles["id"]; ?></td>
                                                    <td><?php echo $roles["role_name"]; ?></td>
                                                    <td>
                                                       
                                                        <a class=" btn-sm">
                                                            <i class="fas fa-edit " href="<?php echo "../ajax/ogrenci_sil.php?id=".$roles["id"]; ?>"></i> Edit
                                                        </a>
                                                        <a class=" btn-sm">
                                                            <i class="fa-regular fa-trash-can" href="<?php echo "../ajax/ogrenci_sil.php?id=".$roles["id"]; ?>"></i> Delete
                                                        </a>
                                                        <a class=" btn-sm">
                                                            <i class="fas fa-edit " id="edit2"></i> Edit2
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

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
