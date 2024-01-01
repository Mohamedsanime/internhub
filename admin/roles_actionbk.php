<?php
#include 'database.php';
include 'roles_functions.php';

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


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $role_id = sanitizeInput($_POST['id']);
    $role_name = sanitizeInput($_POST['role_name']);
   
    switch ($action) {
        case 'Create':
            $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
            $stmt->bind_param("s", $role_name);
            break;

        case 'Read':
            $stmt = $conn->prepare("SELECT * FROM roles WHERE role_name = ?");
            $stmt->bind_param("s", $role_name);
            break;

        case 'Update':
            // Sanitize and assign variables
            $role_name = $conn->real_escape_string($_POST['role_name']);
            
             // Prepare SQL statement
            $stmt = $conn->prepare("UPDATE roles SET role_name = ?  WHERE id = ?");
            $stmt->bind_param("s", $role_name);
            
            // Execute and check for errors
            if ($stmt->execute()) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $stmt->error;
            }
            
            $stmt->close();
            break;


        case 'Delete':
            $stmt = $conn->prepare("DELETE FROM roles WHERE role_name = ?");
            $stmt->bind_param("s", $role_name);
            break;

        default:
            echo "Invalid action";
            exit;
    }

    if ($stmt) {
        if ($stmt->execute()) {
            echo "Operation $action successful.";
            if ($action == 'Read') {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<br>Role Name: " . $row["role_name"];
                    // Display other fields as needed
                }
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    
}
else {
    // Handle GET request - Fetch and display data
    echo displayData();
}

function displayData() {
    global $conn;
    $output = "";
    $sql = "SELECT * FROM roles";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Escape all data to prevent XSS attacks
            $roleId = htmlspecialchars($row["id"], ENT_QUOTES);
            $roleName = htmlspecialchars($row["role_name"], ENT_QUOTES);

            // Generate the Edit button with proper data for each row
            $editButton = "<button onclick='openEditModal(\"" 
            . htmlspecialchars($row["role_name"], ENT_QUOTES) . "\")'> <i class='fas fa-edit'></i></button>";

           # $editButton = "<button onclick='openEditModal(\"$orgCode\", \"$name\", \"$contactName\", \"$contactPosition\", \"$email\", \"$website\", \"$phone1\", \"$phone2\", \"$address\")'>Edit</button>";

            // Construct the table row
            $output .= "<tr>";
            $output .= "<td>$roleName</td>";
            $output .= "<td>$editButton <button onclick='deleteRecord(\"$roleName\")'><i class='fas fa-trash-alt'></i></button></td>";
            $output .= "</tr>";
        }
    } else {
        $output .= "<tr><td colspan='9'>No records found</td></tr>";
    }

    return $output;
}


#echo "<tr><td>Test Data</td><td>More Test Data</td></tr>";
$conn->close();
?>