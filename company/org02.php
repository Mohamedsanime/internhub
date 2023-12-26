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
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Organization</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <!-- Edit form fields -->
                            <!-- Hidden field for Org Code (used as identifier for update) -->
                            <input type="hidden" id="editOrgCode" name="org_code">

                            <label for="editName">Name:</label>
                            <input type="text" id="editName" name="name" class="form-control" required>

                            <label for="editContactName">Contact Name:</label>
                            <input type="text" id="editContactName" name="contactname" class="form-control">

                            <label for="editEmail">Email:</label>
                            <input type="email" id="editEmail" name="email" class="form-control">

                            <!-- Other fields as per your database schema -->
                            <!-- Example: <input type="text" id="editAddress" name="address"> -->
                            <input type="hidden" id="editAction" name="action" value="Update">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
                    </div>
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

            window.openEditModal = function(orgCode, name) {
                // Populate modal fields
                // Populate modal fields with existing data
                $('#editOrgCode').val(orgCode);
                $('#editName').val(name);
                $('#editContactName').val(contactName);
                $('#editEmail').val(email);

                // Open the modal
                $('#editModal').modal('show');
            };

            window.deleteRecord = function(orgCode) {
                $.ajax({
                    type: "POST",
                    url: "crud.php",
                    data: { action: "Delete", org_code: orgCode },
                    success: function() {
                        loadData();
                    }
                });
            };

            function loadData() {
                $.ajax({
                type: "GET",
                url: "crud.php",
                success: function(data) {
                    $('#dataRows').html(data);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: " + error);
                }
            });
        }
    });
    </script>

    <!-- Include Bootstrap JS for modal functionality -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
