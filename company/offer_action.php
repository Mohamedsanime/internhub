<?php
session_start();
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";
#include 'database.php';
include 'functions.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";
$user_id = $_SESSION["id"];
// Create connection
//<span class="math-inline">conn \= new mysqli\(</span>servername, $username, $password, $dbname);
$conn = new mysqli($servername, $username, $password, $dbname);

$sql= $conn->prepare("SELECT org_id FROM supervisor WHERE user_id = ?");
$sql->bind_param("i", $user_id);
$sql->execute();
$sql->bind_result($org_id);
$sql -> fetch();
$orgId = $org_id;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $description = sanitizeInput($_POST['description']);
    $fromdate = sanitizeInput($_POST['fromdate']);
    $todate = sanitizeInput($_POST['todate']);
    $location = sanitizeInput($_POST['location']);
    $requirement = sanitizeInput($_POST['requirement']);
    $appdeadline = sanitizeInput($_POST['appdeadline']);
    $requested = sanitizeInput($_POST['requested']);
    $filled = sanitizeInput($_POST['filled']);
    $notes = sanitizeInput($_POST['notes']);

    switch ($action) {
        case 'Create':
            $stmt = $conn->prepare("INSERT INTO offers (description, fromdate, todate, location, requirement, appdeadline, requested, filled, notes, org_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssiisi", $description, $fromdate, $todate, $location, $requirement, $appdeadline, $requested, $filled, $notes, $orgId);
            break;

        case 'Read':
            $stmt = $conn->prepare("SELECT * FROM offers WHERE org_id = ?");
            $stmt->bind_param("s", $orgId);
            break;

        case 'Update':
            // Sanitize and assign variables
            $description = $conn->real_escape_string($_POST['description']);
            $fromdate = $conn->real_escape_string($_POST['fromdate']);
            $todate = $conn->real_escape_string($_POST['todate']);
            $location = $conn->real_escape_string($_POST['location']);
            $requirement = $conn->real_escape_string($_POST['requirement']);
            $appdeadline = $conn->real_escape_string($_POST['appdeadline']);
            $requested = $conn->real_escape_string($_POST['requested']);
            $filled = $conn->real_escape_string($_POST['$filled']);
            $notes = $conn->real_escape_string($_POST['$notes']);
            
             // Prepare SQL statement
            $stmt = $conn->prepare("UPDATE offers SET description = ?, fromdate = ?, todate = ?, location = ?, requirement = ?, appdeadline = ?, requested = ?, 
            filled = ?, notes =? WHERE id = ?");
            $stmt->bind_param("ssssssiis", $description, $fromdate, $todate, $location, $requirement, $appdeadline, $requested, $filled, $notes);
            
            // Execute and check for errors
            if ($stmt->execute()) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $stmt->error;
            }
            
            $stmt->close();
            break;


        case 'Delete':
            $stmt = $conn->prepare("DELETE FROM companies WHERE id = ?");
            $stmt->bind_param("s", $Id);
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
                    echo "<br>Id: " . $row["id"]. ", Description: " . $row["descriptionname"];
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
    //$user_id = $_SESSION["id"];
    $query= $conn->prepare("SELECT c.* FROM companies c JOIN supervisor u ON c.id = u.org_id WHERE u.user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Escape all data to prevent XSS attacks
            $description = htmlspecialchars($row["description"], ENT_QUOTES);
            $fromdate = htmlspecialchars($row["fromdate"], ENT_QUOTES);
            $todate = htmlspecialchars($row["todate"], ENT_QUOTES);
            $location = htmlspecialchars($row["location"], ENT_QUOTES);
            $appdeadline = htmlspecialchars($row["appdeadline"], ENT_QUOTES);
            $requested = htmlspecialchars($row["requested"], ENT_QUOTES);
            $filled = htmlspecialchars($row["filled"], ENT_QUOTES);
            $notes = htmlspecialchars($row["notes"], ENT_QUOTES);
            $jsonEncodedAddress = json_encode($address);


            // Generate the Edit button with proper data for each row
            $editButton = "<button onclick='openEditModal(\"" 
            . htmlspecialchars($row["description"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["fromdate"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["todate"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["location"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["requirement"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["appdeadline"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["requested"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["filled"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["notes"], ENT_QUOTES) . "\")'> <i class='fas fa-edit'></i></button>";

           # $editButton = "<button onclick='openEditModal(\"$orgCode\", \"$name\", \"$contactName\", \"$contactPosition\", \"$email\", \"$website\", \"$phone1\", \"$phone2\", \"$address\")'>Edit</button>";

            // Construct the table row
            $output .= "<tr>";
            $output .= "<td>$description</td>";
            $output .= "<td>$fromdate</td>";
            $output .= "<td>$todate</td>";
            $output .= "<td>$location</td>";
            $output .= "<td>$requirement</td>";
            $output .= "<td>$appdeadline</td>";
            $output .= "<td>$requested</td>";
            $output .= "<td>$filled</td>";
            $output .= "<td>$notes</td>";
            $output .= "<td>$editButton <button onclick='deleteRecord(\"$Id\")'><i class='fas fa-trash-alt'></i></button></td>";
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