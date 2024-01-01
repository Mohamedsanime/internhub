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


// Fetch roles
$country = $db->query("SELECT * FROM countries");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Countries Data Management</title>

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
                                <h1 class="m-0"><b>Countries Data Management</b></h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#countryModal">
                                        New Country
                                    </button>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
            </div>

            <!-- Create Role Form -->


            <div class="modal fade" id="countryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><b>New Country</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form  id="countryForm">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="numCode">Code:</label>
                                        <input type="text" name="num_code" class="form-control" id="numCode" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="alpha2Code">Alpha 2 Code:</label>
                                        <input type="text" name="alpha_2_code" class="form-control" id="alpha2Code">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="alpha3Code">Alpha 3 Code:</label>
                                        <input type="text" name="alpha_3_code" class="form-control" id="alpha3Code" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="enShortName">Short Nme:</label>
                                        <input type="text" name="en_short_Name" class="form-control" id="enShortName">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="nationality">Nationality:</label>
                                        <input type="text" name="nationality" class="form-control" id="nationality" >
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="action" name="action" value="Add">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                                        <table id="coutryTable"
                                            class="table table-bordered table-striped dataTable dtr-inline"
                                            aria-describedby="example1_info">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Alpha 2 Code</th>
                                                    <th>Alpha 3 Code</th>
                                                    <th>Short Name</th>
                                                    <th>Nationality</th>
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
            var table = $('#countryTable').DataTable( {
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
            $('#addCountryBtn').click(function() {
                $('#roleForm')[0].reset();
                $('#action').val('Add');
                $('#modalTitle').text('Add Country Data');
                $('#numCode').val('');
            });

            // Form submission
            $('#countryModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();  // Remove any stray backdrops
                });
            $('#countryForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: 'country_action.php',
                    data: formData,
                    success: function() {
                        $('#countryModal').modal('hide');                       
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
                url: 'country_action.php',
                success: function(data) {
                    $('#dataRows').html(data);
                }
            });
        }

        function editCountry(id, name) {
            $('#countryNumCode').val(num_code);
            $('#countryAlpha2Code').val(alpha_2_code);
            $('#countryAlpha3Code').val(alpha_3_code);
            $('#countryNationality').val(nationality);
            $('#countryEnShortName').val(en_short_name);
            $('#action').val('Edit');
            $('#modalTitle').text('Edit Country Data');
            $('#countryModal').modal('show');
        }

        function deleteCountry(id) {
            if (confirm('Are you sure you want to delete this country data?')) {
                $.ajax({
                    type: 'POST',
                    url: 'country_action.php',
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
$db->close();
?>
