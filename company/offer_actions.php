<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$conn = new mysqli($servername, $username, $password, $dbname);

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? 0;

// Fetch the supervisor's organization ID
$orgQuery = $conn->prepare("SELECT org_id FROM supervisor WHERE id = ?");
$orgQuery->bind_param("i", $user_id);
$orgQuery->execute();
$orgResult = $orgQuery->get_result();
$org_id = $orgResult->fetch_assoc()['org_id'];

switch ($action) {
    case 'add':
        // Collect and sanitize input data
        $description = $conn->real_escape_string($_POST['description']);
        $fromdate = $conn->real_escape_string($_POST['fromdate']);
        $todate = $conn->real_escape_string($_POST['todate']);
        $location = $conn->real_escape_string($_POST['location']);
        $requirement = $conn->real_escape_string($_POST['requirement']);
        $appdeadline = $conn->real_escape_string($_POST['appdeadline']);
        $requested = $conn->real_escape_string($_POST['requested']);
        $filled = $conn->real_escape_string($_POST['$filled']);
        $notes = $conn->real_escape_string($_POST['$notes']);
        // ... sanitize other fields ...

        // Insert query
        $insertQuery = "INSERT INTO offers (description, fromdate, todate, location, requirement, appdeadline, requested, filled, notes, org_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssssiiisi", $description, $_POST['fromdate'], $_POST['todate'], $_POST['location'], $_POST['requirement'], 
                    $_POST['appdeadline'], $_POST['requested'], $_POST['filled'], $_POST['notes'], $org_id);
        $stmt->execute();
        break;
    
    case 'edit':
        // Collect and sanitize input data
        $description = $conn->real_escape_string($_POST['description']);
        $fromdate = $conn->real_escape_string($_POST['fromdate']);
        $todate = $conn->real_escape_string($_POST['todate']);
        $location = $conn->real_escape_string($_POST['location']);
        $requirement = $conn->real_escape_string($_POST['requirement']);
        $appdeadline = $conn->real_escape_string($_POST['appdeadline']);
        $requested = $conn->real_escape_string($_POST['requested']);
        $filled = $conn->real_escape_string($_POST['$filled']);
        $notes = $conn->real_escape_string($_POST['$notes']);
        // ... sanitize other fields ...

        // Update query
        $updateQuery = "UPDATE offers SET description = ?, fromdate = ?, todate = ?, location = ?, requirement = ?, appdeadline = ?, 
                    requested = ?, filled = ?, notes = ? WHERE id = ? AND org_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssiiisii", $description, $_POST['fromdate'], $_POST['todate'], $_POST['location'], $_POST['requirement'], 
                    $_POST['appdeadline'], $_POST['requested'], $_POST['filled'], $_POST['notes'], $id, $org_id);
        $stmt->execute();
        break;

    case 'delete':
        // Delete an offer
        $deleteQuery = "DELETE FROM offers WHERE id = ? AND org_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("ii", $id, $org_id);
        $stmt->execute();
        break;

    default:
        // Handle unknown action
        break;
}

header("Location: offers.php");
exit;
?>