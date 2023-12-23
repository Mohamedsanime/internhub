<?php error_reporting (E_ALL ^ E_NOTICE); ?> 
<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";

// Create connection
//<span class="math-inline">conn \= new mysqli\(</span>servername, $username, $password, $dbname);
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve organizations from the database
function getOrganizations() {
    global $conn;
    $sql = "SELECT * FROM organization";
    $result = $conn->query($sql);

    // Create an array to store the results
    $organizations = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $organizations[] = $row;
        }
    }
    return $organizations;
}

// Function to create a new organization
function createOrganization($org_code, $name, $contactname, $contactposition, $email, $website, $phone1, $phone2, $address) {
    global $conn;
    $sql = "INSERT INTO organization (org_code, name, contactname, contactposition, email, website, phone1, phone2, address)
            VALUES ('$org_code', '$name', '$contactname', '$contactposition', '$email', '$website', '$phone1', '$phone2', '$address')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to update an existing organization
function updateOrganization($id, $org_code, $name, $contactname, $contactposition, $email, $website, $phone1, $phone2, $address) {
    global $conn;
    $sql = "UPDATE organization SET org_code='$org_code', name='$name', contactname='$contactname', contactposition='$contactposition', email='$email', website='$website', phone1='$phone1', phone2='$phone2', address='$address' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to delete an organization
function deleteOrganization($id) {
    global $conn;
    $sql = "DELETE FROM organization WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Handle CRUD actions based on form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the action from the form
    $action = $_POST['action'];

    // Perform the requested action
    switch ($action) {
        case 'create':
            $createSuccess = createOrganization($_POST['org_code'], $_POST['name'], $_POST['contactname'], $_POST['contactposition'], $_POST['email'], $_POST['website'], $_POST['phone1'], $_POST['phone2'], $_POST['address']);
            if ($createSuccess) {
                // Display success message
            } else {
                // Display error message
            }
            break;
        case 'update':
            $updateSuccess = updateOrganization($_POST['id'], $_POST['org_code'], $_POST['name'], $_POST['contactname'], $_POST['contactposition'], $_POST['email'], $_POST['website'], $_POST['phone1'], $_POST['phone2'], $_POST['address']);
            if ($updateSuccess) {
                // Display success message
            } else {
                // Display error message
            }
            break;
        case 'delete':
            $deleteSuccess = deleteOrganization($_POST['id']);
            if ($deleteSuccess) {
                // Display success message
            } else {
                // Display error message
            }
            break;
    }
}

// Retrieve organizations for display
$organizations = getOrganizations();

?>

<?php include('header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">Organizations</h1>

            <?php if (isset($createSuccess) && $createSuccess) { ?>
                <div class="alert alert-success">Organization created successfully!</div>
            <?php } elseif (isset($updateSuccess) && $updateSuccess) { ?>
                <div class="alert alert-success">Organization updated successfully!</div>
            <?php } elseif (isset($deleteSuccess) && $deleteSuccess) { ?>
                <div class="alert alert-success">Organization deleted successfully!</div>
            <?php } ?>

            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Org Code</th>
                    <th>Name</th>
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
                <tbody>
                <?php foreach ($organizations as $organization) { ?>
                    <tr>
                        <td><?php echo $organization['id']; ?></td>
                        <td><?php echo $organization['org_code']; ?></td>
                        <td><?php echo $organization['name']; ?></td>
                        <td><?php echo $organization['contactname']; ?></td>
                        <td><?php echo $organization['contactposition']; ?></td>
                        <td><?php echo $organization['email']; ?></td>
                        <td><?php echo $organization['website']; ?></td>
                        <td><?php echo $organization['phone1']; ?></td>
                        <td><?php echo $organization['phone2']; ?></td>
                        <td><?php echo $organization['address']; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $organization['id']; ?>" class="btn btn-primary btn-xs">Edit</a>
                            <a href="delete.php?id=<?php echo $organization['id']; ?>" class="btn btn-danger btn-xs">Delete</a>
                            <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#editModal<?php echo $organization['id']; ?>">Edit</a>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: inline-block;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $organization['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <h2>Create Organization</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="create">
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

