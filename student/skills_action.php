<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$conn = new mysqli($servername, $username, $password, $dbname);
$username = $_SESSION["username"];
$role_name = $_SESSION["role_name"];

$action = $_POST['action'] ?? ''; // Get the action specified in the AJAX call
$user_id = $_SESSION['user_id']; // Retrieve user ID from session
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
switch ($action) {
    case 'add':
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        // Add a new skill
        $description = $_POST['description'];
        $level = $_POST['level'];
        $notes = $_POST['notes'];

        $addQuery = $mysqli->prepare("INSERT INTO skills (description, level, notes, student_id) VALUES (?, ?, ?, ?)");
        $addQuery->bind_param("sssi", $description, $level, $notes, $student_id);
        $success = $addQuery->execute();

        echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'Skill added successfully' : 'Error adding skill']);
        break;

    case 'edit':
        // Edit an existing skill
        $id = $_POST['id'];
        $description = $_POST['description'];
        $level = $_POST['level'];
        $notes = $_POST['notes'];

        $editQuery = $mysqli->prepare("UPDATE skills SET description = ?, level = ?, notes = ? WHERE id = ? AND student_id = ?");
        $editQuery->bind_param("sssii", $description, $level, $notes, $id, $student_id);
        $success = $editQuery->execute();

        echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'Skill updated successfully' : 'Error updating skill']);
        break;

    case 'fetch':
        // Fetch details of a skill for editing
        $id = $_POST['id'];

        $fetchQuery = $mysqli->prepare("SELECT * FROM skills WHERE id = ? AND student_id = ?");
        $fetchQuery->bind_param("ii", $id, $student_id);
        $fetchQuery->execute();
        $result = $fetchQuery->get_result();
        $skill = $result->fetch_assoc();

        echo json_encode($skill);
        break;

    case 'delete':
        // Delete a skill
        $id = $_POST['id'];

        $deleteQuery = $mysqli->prepare("DELETE FROM skills WHERE id = ? AND student_id = ?");
        $deleteQuery->bind_param("ii", $id, $student_id);
        $success = $deleteQuery->execute();

        echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'Skill deleted successfully' : 'Error deleting skill']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

$mysqli->close();
?>
