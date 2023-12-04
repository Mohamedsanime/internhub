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

$sql = "SELECT MONTH(appdate) as month, 
               SUM(CASE WHEN decision = 'Y' THEN 1 ELSE 0 END) as accepted,
               SUM(CASE WHEN decision = 'N' THEN 1 ELSE 0 END) as rejected,
               SUM(CASE WHEN decision = 'P' THEN 1 ELSE 0 END) as pending
        FROM Application
        WHERE YEAR(appdate) = ?
        GROUP BY MONTH(appdate)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($data);

$stmt->close();
$conn->close();
?>
