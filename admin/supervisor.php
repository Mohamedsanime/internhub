<?php
include('../admin/includes/header.php');
include('../admin/includes/topbar.php');
include('../admin/includes/sidebar.php');
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$connname = 'internship';

$conn = new mysqli($host, $username, $password, $connname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch roles
$supervisor = $conn->query("SELECT users.id as usrid, users.name, users.surname, users.email, phone, qualification, gender, active,activatedon,
    deactivatedon, address FROM users  inner join supervisor on supervisor.user_id = users.id where users.rol_id = 3");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supervisors Data Management</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../admin/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../admin/assets/dist/css/adminlte.min.css">
    <script src="https://kit.fontawesome.com/1f952dc3e7.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../admin/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../admin/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../admin/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

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
                                <h1 class="m-0"><b>Supervisors Data Management</b></h1>
                            </div><!-- /.col -->
                            <!-- <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new_supervisor">
                                        New Supervisor
                                    </button>
                                </ol>
                            </div> -->
                            <!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
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
                                                    <th>Name</th>
                                                    <th>Surname</th>
                                                    <th>Email</th>                                                   
                                                    <th>Phone</th>
                                                    <th>qualification</th>
                                                    <th>gender</th>
                                                    <th>active</th>
                                                    <th>Activ. On</th>
                                                    <th>Deactiv. On</th>
                                                    <th>Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $query=$conn->query("SELECT users.id as usrid, users.name, users.surname, users.email, phone, qualification, gender, active,activatedon,
                                            deactivatedon, address FROM users  inner join supervisor on supervisor.user_id = users.id where users.rol_id = 3");
                                            $vrow = $query->fetch_all(MYSQLI_ASSOC);
                                            //$query = "SELECT * FROM tbl_comment WHERE parent_comment_id = :parent_id";
                                           
                                            // $statement->bindParam(':parent_id', $parent_id);

                                            ?>

                                            <?php foreach ($vrow as $supervisor): ?>
                                                <tr>
                                                    <td><?php echo $supervisor["usrid"]; ?></td>
                                                    <td><?php echo $supervisor["name"]; ?></td>
                                                    <td><?php echo $supervisor["surname"]; ?></td>
                                                    <td><?php echo $supervisor["email"]; ?></td>
                                                    <td><?php echo $supervisor["phone"]; ?></td>
                                                    <td><?php echo $supervisor["qualification"]; ?></td>
                                                    <td><?php echo $supervisor["gender"]; ?></td>
                                                    <td><?php echo $supervisor["active"]; ?></td>
                                                    <td><?php echo $supervisor["activatedon"]; ?></td>
                                                    <td><?php echo $supervisor["deactivatedon"]; ?></td>
                                                    <td><?php echo $supervisor["address"]; ?></td>
                                                    <td>
                                                       
                                                        <a class=" btn-sm">
                                                            <i class="fas fa-edit " ></i> 
                                                        </a>
                                                        <a class=" btn-sm">
                                                            <i class="fa-regular fa-trash-can" ></i> 
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
                    {targets:[11],searchable:false}
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
$conn->close();
?>
