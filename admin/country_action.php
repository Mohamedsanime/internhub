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
    $sql = "SELECT * FROM countries";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        $output .= "<td>" . $row["num_code"] . "</td>";
        $output .= "<td>" . htmlspecialchars($row["alpha_2_code"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["alpha_3_code"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["en_short_name"]) . "</td>";
        $output .= "<td>" . htmlspecialchars($row["nationality"]) . "</td>";
        $output .= "<td>";
        // Edit button with Font Awesome icon
        $output .= "<button class='btn' onclick='editCountry(" . $row["num_code"] . ", \"" . htmlspecialchars($row["alpha_2_code"]) . 
            ", \"" . htmlspecialchars($row["alpha_3_code"]) .", \"" . htmlspecialchars($row["en_short_name"]) .
            ", \"" . htmlspecialchars($row["nationality"]) ."\")'><i class='fas fa-edit'></i></button> ";
        // Delete button with Font Awesome icon
        $output .= "<button class='btn' onclick='deleteRole(" . $row["num_code"] . ")'><i class='fas fa-trash-can'></i></button>";
        $output .= "</td>";
        $output .= "</tr>";
    }
    return $output;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'Add') {
        $alpha2Code = $conn->real_escape_string($_POST['alpha_2_code']);
        $alpha3Code = $conn->real_escape_string($_POST['alpha_3_code']);
        $enShortName = $conn->real_escape_string($_POST['en_short_name']);
        $nationality = $conn->real_escape_string($_POST['nationality']);
        $sql = "INSERT INTO countries (alpha_2_code, alpha_3_code, en_short_name, nationality) 
            VALUES ('$alpha2Code', '$alpha3Code', '$enShortName', '$nationality')";
    } elseif ($action == 'Edit') {
        $numCode = $conn->real_escape_string($_POST['num_code']);
        $alpha2Code = $conn->real_escape_string($_POST['alpha_2_code']);
        $alpha3Code = $conn->real_escape_string($_POST['alpha_3_code']);
        $enShortName = $conn->real_escape_string($_POST['en_short_name']);
        $nationality = $conn->real_escape_string($_POST['nationality']);
        $sql = "UPDATE roles SET alpha_2_code = '$alpha2Code', alpha_3_code = '$alpha3Code', en_short_name = '$enShortName', nationality = '$nationality' WHERE num_code = $numCode";
    } elseif ($action == 'Delete') {
        $id = $conn->real_escape_string($_POST['3num_code']);
        $sql = "DELETE FROM countries WHERE num_code = $numCode";
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
