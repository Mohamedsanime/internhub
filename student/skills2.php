<!DOCTYPE html>
<html>
<head>
    <title>Skills Management</title>
    <!-- Include Bootstrap, jQuery, FontAwesome here -->
</head>
<body>
    <div class="container">
        <h2>My Skills</h2>
        <button data-toggle="modal" data-target="#skillModal" onclick="prepareModal('add', null)">Add New Skill</button>

        <!-- Skills Table -->
        <table id="skillsTable">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Level</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // PHP code to fetch skills from database and display
                ?>
            </tbody>
        </table>

        <!-- Skill Modal -->
        <div id="skillModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Skill Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="skillForm">
                        <div class="modal-body">
                            <input type="hidden" id="skillId" name="id">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description">
                            </div>
                            <div class="form-group">
                                <label>Level</label>
                                <input type="text" class="form-control" name="level">
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" name="notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Skill</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#skillForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'skillsaction.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    if(response.status === 'success') {
                        $('#skillModal').modal('hide');
                        location.reload(); // Refresh to update the table
                    }
                },
                error: function() {
                    alert('Error in sending request.');
                }
            });
        });
    });

    function prepareModal(action, id) {
        $('#skillForm')[0].reset(); // Reset the form
        $('#skillId').val(id);
        $('#skillForm').attr('action', action);

        if(action === 'edit') {
            // Fetch and fill data for editing
            // Additional AJAX call to fetch skill data and fill in the form
        }
        // Show the modal
        $('#skillModal').modal('show');
    }

    function deleteSkill(id) {
        if(confirm("Are you sure you want to delete this skill?")) {
            $.ajax({
                url: 'skillsaction.php',
                type: 'POST',
                data: { action: 'delete', id: id },
                success: function(response) {
                    alert(response.message);
                    if(response.status === 'success') {
                        location.reload(); // Refresh to update the table
                    }
                },
                error: function() {
                    alert('Error in sending request.');
                }
            });
        }
    }
    </script>
</body>
</html>
