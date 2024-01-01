<?php
#include 'db.php'; // Include your database connection
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$conn = new mysqli($servername, $username, $password, $dbname);
$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$role_name = $_SESSION["role_name"];
$student_id = 12;


$action = $_POST['action'];
$user_id = $_SESSION['user_id']; // Make sure this is set in your login script

switch ($action) {
    case 'add':
        // Add skill logic
        // You should validate and sanitize these inputs
        $description = $_POST['description'];
        $level = $_POST['level'];
        $notes = $_POST['notes'];

        $stmt = $mysqli->prepare("INSERT INTO skills (description, level, notes, student_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $description, $level, $notes, $student_id);
        if($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Skill added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add skill']);
        }
        break;

    case 'edit':
        // Edit skill logic
        // Validate and sanitize inputs
        $id = $_POST['id'];
        $description = $_POST['description'];
        $level = $_POST['level'];
        $notes = $_POST['notes'];

        $stmt = $mysqli->prepare("UPDATE skills SET description = ?, level = ?, notes = ? WHERE id = ? AND student_id = ?");
        $stmt->bind_param("sssii", $description, $level, $notes, $id, $student_id);
        if($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Skill updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update skill']);
        }
        break;

    case 'delete':
        // Delete skill logic
        $id = $_POST['id'];
        $stmt = $mysqli->prepare("DELETE FROM skills WHERE id = ? AND student_id = ?");
        $stmt->bind_param("ii", $id, $student_id);
        if($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Skill deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete skill']);
        }
        break;

    // ... Add case for 'fetch' if needed ...
}

$mysqli->close();
?>
