<?php
//include '/config/db/config.php';
$db_host = "localhost";
$db_name = "internship";
$db_user = "root";
$db_password = "";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

$sql = "SELECT MONTH(fromdate) AS month, COUNT(*) AS offerCount 
            FROM offers 
            WHERE YEAR(fromdate) = '$selectedYear' 
            GROUP BY MONTH(fromdate)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($data);

$stmt->close();
$conn->close();
?>
