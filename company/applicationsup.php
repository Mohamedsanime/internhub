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


$offersQuery = "SELECT id, description, fromdate, todate, location, requirement FROM offers WHERE appdeadline >= CURDATE()";
$offersResult = $conn->query($offersQuery);

$apps = array("Pending"=>"P", "Accepted"=>"A", "Rejected"=>"R");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Skills Data Management</title>

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
                                <h1 class="m-0"><b>Applications Review</b></h1>
                            </div>
                             <!-- <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <button id="addBtn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#winModal">
                                        New Application
                                    </button>
                                </ol>
                            </div> -->
                        </div>
                    </div>
            </div>
            
            
            <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Add Application</h5>
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
                                            <label for="offer_id">Offers - Deadline >= Current Date</label>
                                            <select name="offer_id" id="offer_id" class="form-control" readonly onchange="fillOfferDetails()">
                                                <option value="">Choose an offer</option>
                                                <?php
                                                    while ($offer = $offersResult->fetch_assoc()) {
                                                        echo "<option value='" . $offer['id'] . "' 
                                                                data-fromdate='" . $offer['fromdate'] . "'
                                                                data-todate='" . $offer['todate'] . "'
                                                                data-location='" . htmlspecialchars($offer['location'], ENT_QUOTES) . "'
                                                                data-requirement='" . htmlspecialchars($offer['requirement'], ENT_QUOTES) . "'>" 
                                                            . htmlspecialchars($offer['description']) . 
                                                            "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="ofromdate">From</label>
                                        <input type="date" class="form-control" id="ofromdate" name="ofromdate"  readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="otodate">To</label>
                                        <input type="date" class="form-control" id="otodate" name="otodate"  readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="olocation">Location</label>
                                        <input type="text" class="form-control" id="olocation" name="olocation"  readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="otodate">Requirement</label>
                                        <input type="text" class="form-control" id="orequirement" name="orequirement"  readonly>
                                    </div>
                                    <hr>
                                    <div class="form-group col-md-4">
                                        <label for="date">Application Date</label>
                                        <input type="date" class="form-control" id="date" name="date" readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control" id="description" name="description" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="fromdate">From</label>
                                        <input type="date" class="form-control" id="fromdate" name="fromdate" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="todate">To</label>
                                        <input type="date" class="form-control" id="todate" name="todate" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="notes">Notes</label>
                                        <input type="textarea" class="form-control" id="notes" name="notes" readonly>
                                    </div>
                                    <div class="col-md-6"> 
                                        <div class="form-group">
                                            <label for="decision">Decision</label>
                                            <select name= "decision" id= "decision" class="form-control" required >
                                                <option value="A">Accepted</option>
                                                <option value="R">Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="supervisor">Comments</label>
                                        <input type="textarea" class="form-control" id="supervisor" name="supervisor" >
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
                                <!-- Status Filter Dropdown -->
                                 <!--   <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <div class="form-group">
                                                <label for="appFilter">Filter by Application Status:</label>
                                                <select class="form-control" id="appFilter">
                                                    <option value="">All Applications</option>
                                                    <?php foreach ($apps as $key =>$app): ?>
                                                    <option value="<?php echo htmlspecialchars($app); ?>">
                                                    <?php echo htmlspecialchars($key); ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>appdate</th>
                                                            <th>Description</th>
                                                            <th>From</th>
                                                            <th>To</th>
                                                            <th>Company</th>
                                                            <th>Status</th>
                                                            <th>Supervisor</th>
                                                            <th>Actions</th>
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
            function sort(obj) {
                var th = obj.cellIndex + 1;        
                    alert(th);

            /*... and then I followed the procedure in sorting a table in
            https://www.w3schools.com/howto/howto_js_sort_table.asp */
        }
        function fillOfferDetails() {
            var offerSelect = document.getElementById('offer_id');
            var selectedOption = offerSelect.options[offerSelect.selectedIndex];

            document.getElementById('ofromdate').value = selectedOption.getAttribute('data-fromdate');
            document.getElementById('otodate').value = selectedOption.getAttribute('data-todate');
            document.getElementById('olocation').value = selectedOption.getAttribute('data-location');
            document.getElementById('orequirement').value = selectedOption.getAttribute('data-requirement');
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
                $('#modalTitle').text('Add Application');
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
                    url: 'application_action1.php',
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
            
                // Application filter change event
                    $('#appFilter').on('change', function() {
                    var selectedAppName = $(this).val();
                    console.log("Filtering by Status:", selectedAppName); // Debugging
                    table.column(6).search(selectedAppName).draw();
                });
        });

        
        function loadData() {
            $.ajax({
                type: 'GET',
                url: 'application_action1.php',
                success: function(data) {
                    $('#dataRows').html(data);
                }
            });
        }
      
        function editBtn(id, offer_id, date, description, fromdate, todate, company, decision, supervisor, notes) {
            $('#id').val(id);
            $('#offer_id').val(offer_id);
            $('#date').val(date);
            $('#description').val(description);
            $('#fromdate').val(fromdate);
            $('#todate').val(todate);
            $('#company').val(company);
            $('#decision').val(decision);
            $('#supervisor').val(supervisor);
            $('#notes').val(notes);
            fillOfferDetails();
            $('#action').val('Edit');
            $('#modalTitle').text('Review Application');
            $('#winModal').modal('show');
        }
        function deleteBtn(id) {
            if (confirm('Are you sure you want to delete this Application?')) {
                $.ajax({
                    type: 'POST',
                    url: 'application_action1.php',
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
