<?php
#include 'database.php';
include 'functions.php';

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
    $org_code = sanitizeInput($_POST['org_code']);
    $name = sanitizeInput($_POST['name']);
    $contactname = sanitizeInput($_POST['contactname']);
    $contactposition = sanitizeInput($_POST['contactposition']);
    $email = isset($_POST['email']) && validateEmail($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $website = sanitizeInput($_POST['website']);
    $phone1 = sanitizeInput($_POST['phone1']);
    $phone2 = sanitizeInput($_POST['phone2']);
    $address = sanitizeInput($_POST['address']);

    switch ($action) {
        case 'Create':
            $stmt = $conn->prepare("INSERT INTO companies (org_code, name, contactname, contactposition, email, website, phone1, phone2, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $org_code, $name, $contactname, $contactposition, $email, $website, $phone1, $phone2, $address);
            break;

        case 'Read':
            $stmt = $conn->prepare("SELECT * FROM companies WHERE org_code = ?");
            $stmt->bind_param("s", $org_code);
            break;

        case 'Update':
            // Sanitize and assign variables
            $org_code = $conn->real_escape_string($_POST['org_code']);
            $name = $conn->real_escape_string($_POST['name']);
            $contactname = $conn->real_escape_string($_POST['contactname']);
            $contactposition = $conn->real_escape_string($_POST['contactposition']);
            $email = $conn->real_escape_string($_POST['email']);
            $website = $conn->real_escape_string($_POST['website']);
            $phone1 = $conn->real_escape_string($_POST['phone1']);
            $phone2 = $conn->real_escape_string($_POST['phone2']);
            $address = $conn->real_escape_string($_POST['address']);
            
             // Prepare SQL statement
            $stmt = $conn->prepare("UPDATE companies SET name = ?, contactname = ?, contactposition = ?, email = ?, website = ?, phone1 = ?, phone2 = ?, address = ? WHERE org_code = ?");
            $stmt->bind_param("sssssssss", $name, $contactname, $contactposition, $email, $website, $phone1, $phone2, $address, $org_code);
            
            // Execute and check for errors
            if ($stmt->execute()) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $stmt->error;
            }
            
            $stmt->close();
            break;


        case 'Delete':
            $stmt = $conn->prepare("DELETE FROM companies WHERE org_code = ?");
            $stmt->bind_param("s", $org_code);
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
                    echo "<br>Org Code: " . $row["org_code"]. ", Name: " . $row["name"];
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
    $sql = "SELECT * FROM companies";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Escape all data to prevent XSS attacks
            $orgCode = htmlspecialchars($row["org_code"], ENT_QUOTES);
            $name = htmlspecialchars($row["name"], ENT_QUOTES);
            $contactName = htmlspecialchars($row["contactname"], ENT_QUOTES);
            $contactPosition = htmlspecialchars($row["contactposition"], ENT_QUOTES);
            $email = htmlspecialchars($row["email"], ENT_QUOTES);
            $website = htmlspecialchars($row["website"], ENT_QUOTES);
            $phone1 = htmlspecialchars($row["phone1"], ENT_QUOTES);
            $phone2 = htmlspecialchars($row["phone2"], ENT_QUOTES);
            $address = htmlspecialchars($row["address"], ENT_QUOTES);
            $jsonEncodedAddress = json_encode($address);


            // Generate the Edit button with proper data for each row
            $editButton = "<button onclick='openEditModal(\"" 
            . htmlspecialchars($row["org_code"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["name"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["contactname"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["contactposition"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["email"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["website"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["phone1"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["phone2"], ENT_QUOTES) . "\", \"" 
            . htmlspecialchars($row["address"], ENT_QUOTES) . "\")'> <i class='fas fa-edit'></i></button>";

           # $editButton = "<button onclick='openEditModal(\"$orgCode\", \"$name\", \"$contactName\", \"$contactPosition\", \"$email\", \"$website\", \"$phone1\", \"$phone2\", \"$address\")'>Edit</button>";

            // Construct the table row
            $output .= "<tr>";
            $output .= "<td>$orgCode</td>";
            $output .= "<td>$name</td>";
            $output .= "<td>$contactName</td>";
            $output .= "<td>$contactPosition</td>";
            $output .= "<td>$email</td>";
            $output .= "<td>$website</td>";
            $output .= "<td>$phone1</td>";
            $output .= "<td>$phone2</td>";
            $output .= "<td>$address</td>";
            $output .= "<td>$editButton <button onclick='deleteRecord(\"$orgCode\")'><i class='fas fa-trash-alt'></i></button></td>";
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