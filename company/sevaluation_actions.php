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

//$stdQuery = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
//$stdQuery->bind_param("i", $user_id);
//$stdQuery->execute();
//$stdResult = $stdQuery->get_result();
//if ($stdRow = $stdResult->fetch_assoc()) {
//    $student_id = $stdRow['id'];
//} else {
//    echo "Student not found.";
//    exit;
//}

$supQuery = $conn->prepare("SELECT id FROM supervisor WHERE user_id = ?");
$supQuery->bind_param("i", $user_id);
$supQuery->execute();
$supResult = $supQuery->get_result();
if ($supRow = $supResult->fetch_assoc()) {
    $sup_id = $supRow['id'];
} else {
    echo "Supervisor not found.";
    exit;
}
//echo "<pre>";
//print_r($_SESSION);
//print ($student_id);
//echo "</pre>";

function displayData($conn) {
    $output = "";
    $sql = "SELECT * FROM sevaluation";
    $result = $conn->query($sql);



    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        //$output .= "<td>" . $row["id"] . "</td>";
        $output .= "<td>" . htmlspecialchars($row["student_id"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["interest"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["attendance"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["technical"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["general"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["overall"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["summary"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["comments"]) . "</td>";
        $output .= "<td>";
        // Edit button with Font Awesome icon
        $output = "<button class='btn' onclick='editBtn(\"" . $row["id"] . "\", \"" . $row["interest"] . "\", \"" . $row["attendance"] . "\", \"" . $row["student_id"]
          . "\", \"" . $row["technical"] . "\", \"" . $row["general"] . "\", \"" . $row["overall"] . "\", \"" . $row["summary"] . "\", \"" . $row["comments"] . "\")'><i class='fas fa-edit'></i></button> ";

        // Delete button with Font Awesome icon
        $output .= "<button class='btn' onclick='deleteBtn(" . $row["id"] . ")'><i class='fas fa-trash-can'></i></button>";
        $output .= "</td>";
        $output .= "</tr>";
    }
    return $output;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    echo "<pre>Posted Data:\n";
    print_r($_POST);
    echo "</pre>";

    if ($action == 'Add') {
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $interest = $conn->real_escape_string($_POST['interest']);
        $attendanceel = $conn->real_escape_string($_POST['attendance']);
        $technical = $conn->real_escape_string($_POST['technical']);
        $general = $conn->real_escape_string($_POST['general']);
        $overall = $conn->real_escape_string($_POST['overall']);
        $summary = $conn->real_escape_string($_POST['summary']);
        $comments = $conn->real_escape_string($_POST['comments']);
        //$student_id = 2;

        $sql = "INSERT INTO sevaluation (interest, attendance, technical, general, overall, summary, comments, sup_id, student_id) 
        VALUES ('$interest', '$attendance', '$technical', '$general', '$overall', '$summary', '$comments', '$sup_id', '$student_id')";
    } elseif ($action == 'Edit') {
        $id = $conn->real_escape_string($_POST['id']);
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $interest = $conn->real_escape_string($_POST['interest']);
        $attendance = $conn->real_escape_string($_POST['attendance']);
        $technical = $conn->real_escape_string($_POST['technical']);
        $general = $conn->real_escape_string($_POST['general']);
        $overall = $conn->real_escape_string($_POST['overall']);
        $summary = $conn->real_escape_string($_POST['summary']);
        $comments = $conn->real_escape_string($_POST['comments']);
        $sql = "UPDATE sevaluation SET interest = '$interest', attendance = '$attendance', technical = '$technical', 
        general = '$general', overall = '$overall', summary = '$summary', comments = '$comments', student_id = '$student_id' WHERE id = $id";
    } elseif ($action == 'Delete') {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "DELETE FROM sevaluation WHERE id = $id";
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
