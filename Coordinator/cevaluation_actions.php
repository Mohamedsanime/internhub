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

$supQuery = $conn->prepare("SELECT id FROM coordinator WHERE user_id = ?");
$supQuery->bind_param("i", $user_id);
$supQuery->execute();
$supResult = $supQuery->get_result();
if ($supRow = $supResult->fetch_assoc()) {
    $cor_id = $supRow['id'];
} else {
    echo "Coordinator not found.";
    exit;
}
//echo "<pre>";
//print_r($_SESSION);
//print ($student_id);
//echo "</pre>";

function displayData($conn) {
    $output = "";
    $sql = "SELECT * FROM cevaluation";
    $result = $conn->query($sql);

     // Define mappings for radio button values to labels
     $radioMapping = ['P' => 'Poor', 'F' => 'Fair', 'G' => 'Good', 'E' => 'Excellent'];
     $radioMapping2 = ['U' => 'Unsatisfactory', 'S' => 'Satisfactory'];

     while ($row = $result->fetch_assoc()) {
        $std = $row['student_id'];
        $stdQuery = "SELECT concat(u.name, ' ',u.surname) as student FROM students s 
                JOIN users u ON s.user_id=u.id  where s.id = $std";
        $stdResult = $conn->query($stdQuery);
        if ($stdRow = $stdResult->fetch_assoc()) {
            $student= $stdRow['student'];
        } else {
            exit;
        }

        $output .= "<tr>";
        $output .= "<td>" . htmlspecialchars($row["student_id"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($radioMapping[$row["quality"]] ?? 'N/A') . "</td>";
        $output .= "<td>" . htmlspecialchars($radioMapping[$row["itwork"]] ?? 'N/A') . "</td>";
        $output .= "<td>" . htmlspecialchars($radioMapping[$row["knowledge"]] ?? 'N/A') . "</td>";
        $output .= "<td>" . htmlspecialchars($radioMapping[$row["answers"]] ?? 'N/A') . "</td>";
        $output .= "<td>" . htmlspecialchars($radioMapping2[$row["overall"]] ?? 'N/A') . "</td>";
        $output .= "<td>" . htmlspecialchars($row["comments"]) . "</td>";
        $output .= "<td>";
        // Edit button with Font Awesome icon
        $output .= "<button class='btn' onclick='editBtn(\"" . $row["id"] . "\", \"" . $row["quality"] . "\", \"" . $row["itwork"] . "\", \"" . $student
          . "\", \"" . $row["knowledge"] . "\", \"" . $row["answers"] . "\", \"" . $row["overall"] . "\", \"" . $row["comments"] . "\")'><i class='fas fa-edit'></i></button> ";

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

        $student_id = $conn->real_escape_string($_POST['student_id']);
        $quality = $conn->real_escape_string($_POST['quality']);
        $itwork = $conn->real_escape_string($_POST['itwork']);
        $knowledge = $conn->real_escape_string($_POST['knowledge']);
        $answers = $conn->real_escape_string($_POST['answers']);
        $overall = $conn->real_escape_string($_POST['overall']);
        $comments = $conn->real_escape_string($_POST['comments']);
        //$student_id = 2;
        $sql = "INSERT INTO cevaluation (quality, itwork, knowledge, answers, overall, comments, cor_id, student_id) 
        VALUES ('$quality', '$itwork', '$knowledge', '$answers', '$overall', '$comments', '$cor_id', '$student_id')";
    } elseif ($action == 'Edit') {
        $id = $conn->real_escape_string($_POST['id']);
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $interest = $conn->real_escape_string($_POST['quality']);
        $attendance = $conn->real_escape_string($_POST['itwork']);
        $technical = $conn->real_escape_string($_POST['knowledge']);
        $general = $conn->real_escape_string($_POST['answers']);
        $overall = $conn->real_escape_string($_POST['overall']);
        $comments = $conn->real_escape_string($_POST['comments']);
        $sql = "UPDATE cevaluation SET quality = '$quality', itwork = '$itwork', knowledge = '$knowledge', 
        answers = '$answers', overall = '$overall', comments = '$comments', student_id = '$student_id' WHERE id = $id";
    } elseif ($action == 'Delete') {
        $id = $conn->real_escape_string($_POST['id']);
        $sql = "DELETE FROM cevaluation WHERE id = $id";
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
