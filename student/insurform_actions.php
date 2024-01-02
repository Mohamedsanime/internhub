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
    $output = "";
    $sql = "SELECT i.* , concat(u.name, ' ', u.surname) as student
            FROM insuranceform i 
            JOIN students s ON i.student_id = s.id
            JOIN users u ON s.user_id = u.id";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        //$output .= "<td>" . $row["id"] . "</td>";
        $output .= "<td>" . htmlspecialchars($row["student"]) . "</td>";
        //$output .= "<td>" . htmlspecialchars($row["student_id"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["submiton"]) . "</td>";
        //$output .= "<td>" . htmlspecialchars($row["decision"]) . "</td>";
        $output .= "<td>" . htmlspecialchars(getDecisionText($row["decision"])) . "</td>";
        //$output .= "<td>" . htmlspecialchars($row["decisiondate"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["notes"]) . "</td>";
        $output .= "<td>";
        // Edit button with Font Awesome icon
        //$output .= "<button class='editBtn' onclick='editBtn("  . htmlspecialchars($row["description"]) 
        //. ", \"" . htmlspecialchars($row["fromdate"]). ", \"" . htmlspecialchars($row["todate"]). ", \"" . htmlspecialchars($row["notes"]). "\")'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='editBtn' onclick='editBtn($row["id"],$row["description"],$row["fromdate"],$row["todate"],$row["notes"])'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='btn' onclick='editBtn()'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='btn' onclick='editBtn(\"" . htmlspecialchars($row["description"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["fromdate"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["todate"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["notes"], ENT_QUOTES) . "\")'><i class='fas fa-edit'></i></button> ";
        $output .= "<button class='btn' onclick='editBtn(\"" . $row["id"] . "\", \"" . $row["submiton"] . "\", \"" . $row["decision"] . "\", \"" 
        . $row["student"] . "\", \"". $row["notes"] . "\")'><i class='fas fa-edit'></i></button> ";
        
        // Delete button with Font Awesome icon
        $output .= "<button class='btn' onclick='deleteBtn(" . $row["id"] . ")'><i class='fas fa-trash-can'></i></button>";
        $output .= "</td>";
        $output .= "</tr>";
    }
    return $output;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
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
    if ($action == 'Add') {
       // $student_id = $conn->real_escape_string($_POST['student_id']);
        $submiton = $conn->real_escape_string($_POST['submiton']);
        //$decision = $conn->real_escape_string($_POST['decision']);
        //$decisiondate = $conn->real_escape_string($_POST['decisiondate']);
        $notes = $conn->real_escape_string($_POST['notes']);
        //$student_id = 2;

        $sql = "INSERT INTO insuranceform (submiton, notes, student_id) VALUES ('$submiton', '$notes', '$student_id')";
    } elseif ($action == 'Edit') {
        $id = $conn->real_escape_string($_POST['id']);
        $submiton = $conn->real_escape_string($_POST['submiton']);
        $decision = $conn->real_escape_string($_POST['decision']);
        $decisiondate = $conn->real_escape_string($_POST['decisiondate']);
        //$student_id = $conn->real_escape_string($_POST['student_id']);
        $notes = $conn->real_escape_string($_POST['notes']);
        $sql = "UPDATE insuranceform SET submiton = '$submiton', decision = '$decision', decisiondate = '$decisiondate', student_id = '$student_id', notes = '$notes' WHERE id = $id";
    } elseif ($action == 'Delete') {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "DELETE FROM insuranceform WHERE id = $id";
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
