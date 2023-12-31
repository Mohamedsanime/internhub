<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'internship';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? '';

if ($action == 'Add') {
    $roleName = $conn->real_escape_string($_POST['role_name']);
    $conn->query("INSERT INTO roles (role_name) VALUES ('$roleName')");
} elseif ($action == 'Edit') {
    $id = $conn->real_escape_string($_POST['id']);
    $roleName = $conn->real_escape_string($_POST['role_name']);
    $conn->query("UPDATE roles SET role_name = '$roleName' WHERE id = $id");
} elseif ($action == 'Delete') {
    $id = $conn->real_escape_string($_POST['id']);
    $conn->query("DELETE FROM roles WHERE id = $id");
}

echo "Success";

$conn->close();
?>
