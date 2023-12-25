
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$conn = new mysqli($servername, $username, $password, $dbname);
$comp = $conn->query("SELECT * FROM organization");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organization Management</title>
    <!-- Include Bootstrap CSS for styling and modal functionality -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include jQuery for AJAX requests -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Organization Form</h2>
        <form id="orgForm" action="crud.php" method="post">
            <!-- Form fields here -->
            <label for="org_code">Organization Code:</label><br>
        <input type="text" id="org_code" name="org_code" required><br>

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="contactname">Contact Name:</label><br>
        <input type="text" id="contactname" name="contactname"><br>

        <label for="contactposition">Contact Position:</label><br>
        <input type="text" id="contactposition" name="contactposition"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="website">Website:</label><br>
        <input type="url" id="website" name="website"><br>

        <label for="phone1">Phone 1:</label><br>
        <input type="tel" id="phone1" name="phone1"><br>

        <label for="phone2">Phone 2:</label><br>
        <input type="tel" id="phone2" name="phone2"><br>

        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address"><br><br>

            <input type="hidden" id="formAction" name="action" value="Create">
            <input type="submit" value="Submit">
        </form>

        <h2 class="mt-5">Organizations</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Org Code</th>
                    <th>Name</th>
                    <!-- other headers -->
                    <th>Contact Name</th>
                    <th>Contact Position</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Phone 1</th>
                    <th>Phone 2</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dataRows">
                <!-- Data will be loaded here via AJAX -->
            </tbody>
        </table>

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Organization</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editOrgCode" name="org_code">

                    <div class="form-group">
                        <label for="editName">Name:</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="editContactName">Contact Name:</label>
                        <input type="text" class="form-control" id="editContactName" name="contactname">
                    </div>

                    <div class="form-group">
                        <label for="editContactPosition">Contact Position:</label>
                        <input type="text" class="form-control" id="editContactPosition" name="contactposition">
                    </div>

                    <div class="form-group">
                        <label for="editEmail">Email:</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>

                    <div class="form-group">
                        <label for="editWebsite">Website:</label>
                        <input type="url" class="form-control" id="editWebsite" name="website">
                    </div>

                    <div class="form-group">
                        <label for="editPhone1">Phone 1:</label>
                        <input type="tel" class="form-control" id="editPhone1" name="phone1">
                    </div>

                    <div class="form-group">
                        <label for="editPhone2">Phone 2:</label>
                        <input type="tel" class="form-control" id="editPhone2" name="phone2">
                    </div>

                    <div class="form-group">
                        <label for="editAddress">Address:</label>
                        <input type="text" class="form-control" id="editAddress" name="address">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            loadData();

            $('#orgForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "crud.php",
                    data: formData,
                    success: function() {
                        loadData();
                    }
                });
            });

            $('#saveEdit').click(function() {
                var editData = $('#editForm').serialize();
                $.ajax({
                    type: "POST",
                    url: "crud.php",
                    data: editData,
                    success: function() {
                        $('#editModal').modal('hide');
                        loadData();
                    }
                });
            });

            window.openEditModal = function(orgCode, name, contactName, contactPosition, email, website, phone1, phone2, address) {
                // Populate the edit form fields with the data passed from the table row
                $('#editOrgCode').val(orgCode);
                $('#editName').val(name);
                $('#editContactName').val(contactName);
                $('#editContactPosition').val(contactPosition);
                $('#editEmail').val(email);
                $('#editWebsite').val(website);
                $('#editPhone1').val(phone1);
                $('#editPhone2').val(phone2);
                var address = JSON.parse('"' + encodedAddress + '"');
                $('#editAddress').val(address);

                // Open the modal
                $('#editModal').modal('show');
            };

            window.deleteRecord = function(orgCode) {
                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                    type: "POST",
                    url: "crud.php",
                    data: { action: "Delete", org_code: orgCode },
                    success: function() {
                        loadData();
                        }
                    });
                }
            };

            $(document).ready(function() {
                loadData();
            });
            
            function loadData() {
                $.ajax({
                    type: "GET",
                    url: "crud.php",
                    success: function(data) {
                        $('#dataRows').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                        console.error("Status: " + status);
                        console.error("Response: " + xhr.responseText);
                    }
                });
            }


    });
    </script>

    <!-- Include Bootstrap JS for modal functionality -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
