<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student 
$user_id = $_SESSION["id"];

$stdQuery = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stdQuery->bind_param("i", $user_id);
$stdQuery->execute();
$stdResult = $stdQuery->get_result();
if ($stdRow = $stdResult->fetch_assoc()) {
    $student_id = $stdRow['id'];
} else {
    echo "Student not found.";
    exit;
}

//echo "<pre>";
//print_r($_SESSION);
//print ($student_id);
//echo "</pre>";

function getDecisionText($decisionCode) {
    $decisionMapping = array(
        'A' => 'Accepted',
        'R' => 'Rejected',
        'P' => 'Pending'
    );

    return isset($decisionMapping[$decisionCode]) ? $decisionMapping[$decisionCode] : 'Unknown';
}

function displayData($conn) {
    $user_id = $_SESSION["id"];

$stdQuery = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stdQuery->bind_param("i", $user_id);
$stdQuery->execute();
$stdResult = $stdQuery->get_result();
if ($stdRow = $stdResult->fetch_assoc()) {
    $student_id = $stdRow['id'];
} else {
    echo "Student not found.";
    exit;
}

    $output = "";
    $sql = "SELECT * FROM Application where student_id = $student_id";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {

        $cpyQuery = $conn->prepare("SELECT c.name FROM companies c JOIN offers o ON c.id = o.org_id WHERE o.id = ?");
        $cpyQuery->bind_param("i", $row["offer_id"]);
        $cpyQuery->execute();
        $cpyResult = $cpyQuery->get_result();
        if ($cpyRow = $cpyResult->fetch_assoc()) {
          $company = $cpyRow['name'];
        } else {
            echo "Company not found.";
            exit;
        }

        $output .= "<tr>";
        //$output .= "<td>" . $row["id"] . "</td>";
        $output .= "<td>" . htmlspecialchars($row["date"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["description"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["fromdate"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["todate"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($company) . "</td>";
        $output .= "<td>" . htmlspecialchars(getDecisionText($row["decision"])) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["supervisor"]) . "</td>";
        $output .= "<td>" . htmlspecialchars(getDecisionText($row["decision2"])) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["coordinator"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["notes"]) . "</td>";
        $output .= "<td>";
        // Edit button with Font Awesome icon
        //$output .= "<button class='editBtn' onclick='editBtn("  . htmlspecialchars($row["description"]) 
        //. ", \"" . htmlspecialchars($row["fromdate"]). ", \"" . htmlspecialchars($row["todate"]). ", \"" . htmlspecialchars($row["notes"]). "\")'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='editBtn' onclick='editBtn($row["id"],$row["description"],$row["fromdate"],$row["todate"],$row["notes"])'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='btn' onclick='editBtn()'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='btn' onclick='editBtn(\"" . htmlspecialchars($row["description"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["fromdate"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["todate"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["notes"], ENT_QUOTES) . "\")'><i class='fas fa-edit'></i></button> ";
        $output .= "<button class='btn' onclick='editBtn(\"" . $row["id"] . "\", \"" . $row["offer_id"] . "\", \"" . $row["date"]
        . "\", \"" . $row["description"] . "\", \"". $row["fromdate"] . "\", \"" . $row["todate"] . "\", \"". $company . "\", \""
        . $row["decision"] . "\", \"". $row["supervisor"] . "\", \"". $row["decision2"] . "\", \"". $row["notes"] . "\", \"". $row["coordinator"] . "\")'><i class='fas fa-edit'></i></button> ";

        // Delete button with Font Awesome icon
        $output .= "<button class='btn' onclick='deleteBtn(" . $row["id"] . ")'><i class='fas fa-trash-can'></i></button>";
        $output .= "</td>";
        $output .= "</tr>";
    }
    return $output;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    //echo "<pre>Posted Data:\n";
    //print_r($_POST);
   // echo "</pre>";

    if ($action == 'Add') {
        $fromdate = $conn->real_escape_string($_POST['fromdate']);
        // $appdt = $_POST['appdate']->format('Y-m-d');
        //$appdate = $conn->real_escape_string($_POST['fromdate']);
        $date = $conn->real_escape_string($_POST['date']);
        //$appdate = $conn->real_escape_string($appdt);
        $description = $conn->real_escape_string($_POST['description']);
       // $student_id = $conn->real_escape_string($_POST['student_id']);
        $offer_id = $conn->real_escape_string($_POST['offer_id']);
        $fromdate = $conn->real_escape_string($_POST['fromdate']);
        $todate = $conn->real_escape_string($_POST['todate']);
        $notes = $conn->real_escape_string($_POST['notes']);
        //$student_id = 2;

        $sql = "INSERT INTO application (description, fromdate, todate, notes, offer_id, student_id, date) 
        VALUES ('$description', '$fromdate', '$todate', '$notes', '$offer_id', '$student_id', '$date')";
    } elseif ($action == 'Edit') {
        $id = $conn->real_escape_string($_POST['id']);
        $description = $conn->real_escape_string($_POST['description']);
        $level = $conn->real_escape_string($_POST['level']);
        $notes = $conn->real_escape_string($_POST['notes']);
        $sql = "UPDATE skills SET description = '$description', level = '$level', notes = '$notes', student_id = '$student_id' WHERE id = $id";
    } elseif ($action == 'Delete') {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "DELETE FROM application WHERE id = $id";
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
