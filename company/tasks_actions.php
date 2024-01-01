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

function displayData($conn) {
    $output = "";
    $sql = "SELECT * FROM skills";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        //$output .= "<td>" . $row["id"] . "</td>";
        $output .= "<td>" . htmlspecialchars($row["description"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["level"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["notes"]) . "</td>";
        $output .= "<td>";
        // Edit button with Font Awesome icon
        //$output .= "<button class='editBtn' onclick='editBtn("  . htmlspecialchars($row["description"]) 
        //. ", \"" . htmlspecialchars($row["fromdate"]). ", \"" . htmlspecialchars($row["todate"]). ", \"" . htmlspecialchars($row["notes"]). "\")'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='editBtn' onclick='editBtn($row["id"],$row["description"],$row["fromdate"],$row["todate"],$row["notes"])'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='btn' onclick='editBtn()'><i class='fas fa-edit'></i></button> ";
        //$output .= "<button class='btn' onclick='editBtn(\"" . htmlspecialchars($row["description"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["fromdate"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["todate"], ENT_QUOTES) . "\", \"" . htmlspecialchars($row["notes"], ENT_QUOTES) . "\")'><i class='fas fa-edit'></i></button> ";
        $output .= "<button class='btn' onclick='editBtn(\"" . $row["id"] . "\", \"" . htmlspecialchars($row["description"], ENT_QUOTES) . "\", \"" . $row["level"] . "\", \"" . htmlspecialchars($row["notes"], ENT_QUOTES) . "\")'><i class='fas fa-edit'></i></button> ";

        // Delete button with Font Awesome icon
        $output .= "<button class='btn' onclick='deleteBtn(" . $row["id"] . ")'><i class='fas fa-trash-can'></i></button>";
        $output .= "</td>";
        $output .= "</tr>";
    }
    return $output;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'Add') {
        $description = $conn->real_escape_string($_POST['description']);
        $level = $conn->real_escape_string($_POST['level']);
        $notes = $conn->real_escape_string($_POST['notes']);
        //$student_id = 2;

        $sql = "INSERT INTO skills (description, level, notes, student_id) VALUES ('$description', '$level', '$notes', '$student_id')";
    } elseif ($action == 'Edit') {
        $id = $conn->real_escape_string($_POST['id']);
        $description = $conn->real_escape_string($_POST['description']);
        $level = $conn->real_escape_string($_POST['level']);
        $notes = $conn->real_escape_string($_POST['notes']);
        $sql = "UPDATE skills SET description = '$description', level = '$level', notes = '$notes', student_id = '$student_id' WHERE id = $id";
    } elseif ($action == 'Delete') {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "DELETE FROM skills WHERE id = $id";
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
