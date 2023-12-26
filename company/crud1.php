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

function displayData() {
    global $conn;
    $sql = "SELECT * FROM organization";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["org_code"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            // Output other fields as needed
            echo "<td>
                    <button onclick='openEditModal(\"" . $row["org_code"] . "\", \"" . $row["name"] . "\", ... )'>Edit</button>
                    <button onclick='deleteRecord(\"" . $row["org_code"] . "\")'>Delete</button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='X'>No records found</td></tr>"; // Replace X with the number of columns
    }
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
            $stmt = $conn->prepare("INSERT INTO organization (org_code, name, contactname, contactposition, email, website, phone1, phone2, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $org_code, $name, $contactname, $contactposition, $email, $website, $phone1, $phone2, $address);
            break;

        case 'Read':
            $stmt = $conn->prepare("SELECT * FROM organization WHERE org_code = ?");
            $stmt->bind_param("s", $org_code);
            break;

        case 'Update':
            $stmt = $conn->prepare("UPDATE organization SET name = ?, contactname = ?, contactposition = ?, email = ?, website = ?, phone1 = ?, phone2 = ?, address = ? WHERE org_code = ?");
            $stmt->bind_param("sssssssss", $name, $contactname, $contactposition, $email, $website, $phone1, $phone2, $address, $org_code);
            break;

        case 'Delete':
            $stmt = $conn->prepare("DELETE FROM organization WHERE org_code = ?");
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

$conn->close();
?>
