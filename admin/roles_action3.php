<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function displayData($conn) {
    $output = "";
    $sql = "SELECT * FROM roles";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        $output .= "<td>" . $row["id"] . "</td>";
        $output .= "<td>" . htmlspecialchars($row["role_name"]) . "</td>";
        $output .= "<td>";
        // Edit button with Font Awesome icon
        $output .= "<button class='btn' onclick='editRole(" . $row["id"] . ", \"" . htmlspecialchars($row["role_name"]) . "\")'><i class='fas fa-edit'></i></button> ";
        // Delete button with Font Awesome icon
        $output .= "<button class='btn' onclick='deleteRole(" . $row["id"] . ")'><i class='fas fa-trash-can'></i></button>";
        $output .= "</td>";
        $output .= "</tr>";
    }
    return $output;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'Add') {
        $roleName = $conn->real_escape_string($_POST['role_name']);
        $sql = "INSERT INTO roles (role_name) VALUES ('$roleName')";
    } elseif ($action == 'Edit') {
        $id = $conn->real_escape_string($_POST['id']);
        $roleName = $conn->real_escape_string($_POST['role_name']);
        $sql = "UPDATE roles SET role_name = '$roleName' WHERE id = $id";
    } elseif ($action == 'Delete') {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "DELETE FROM roles WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo displayData($conn);
}

$conn->close();
?>
