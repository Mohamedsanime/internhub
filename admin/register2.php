<?php
// Database configuration
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbname = 'internship';

// Establish database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $rol_id = intval($_POST['rol_id']);
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $qualification = $conn->real_escape_string($_POST['qualification']);
    $active = intval($_POST['active']);
    $activatedon = $conn->real_escape_string($_POST['activatedon']);
    $deactivatedon = $conn->real_escape_string($_POST['deactivatedon']);
    $address = $conn->real_escape_string($_POST['address']);
    $cny = intval($_POST['cny']);

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (name, surname, email, passwordHash, rol_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $surname, $email, $passwordHash, $rol_id);
        $stmt->execute();
        $user_id = $conn->insert_id;

        // Insert into students table
        $stmt = $conn->prepare("INSERT INTO students (student_id, gender, mobile, qualification, active, activatedon, deactivatedon, address, user_id, cny) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisssii", $student_id, $gender, $mobile, $qualification, $active, $activatedon, $deactivatedon, $address, $user_id, $cny);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        echo "Registration successful";

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 300px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <form action="register2.php" method="post">
        <h2>User Registration</h2>
        <div><label for="name">Name:</label><input type="text" id="name" name="name" required></div>
        <div><label for="surname">Surname:</label><input type="text" id="surname" name="surname" required></div>
        <div><label for="email">Email:</label><input type="email" id="email" name="email" required></div>
        <div><label for="password">Password:</label><input type="password" id="password" name="password" required></div>
        <div><label for="rol_id">Role ID:</label><input type="number" id="rol_id" name="rol_id" required></div>
        <div><label for="student_id">Student ID:</label><input type="text" id="student_id" name="student_id" required></div>
        <div><label for="gender">Gender:</label><input type="text" id="gender" name="gender" required></div>
        <div><label for="mobile">Mobile:</label><input type="text" id="mobile" name="mobile" required></div>
        <div><label for="qualification">Qualification:</label><input type="text" id="qualification" name="qualification"></div>
        <div><label for="active">Active (1 for yes, 0 for no):</label><input type="number" id="active" name="active" required></div>
        <div><label for="activatedon">Activated On (YYYY-MM-DD):</label><input type="date" id="activatedon" name="activatedon"></div>
        <div><label for="deactivatedon">Deactivated On (YYYY-MM-DD):</label><input type="date" id="deactivatedon" name="deactivatedon"></div>
        <div><label for="address">Address:</label><input type="text" id="address" name="address"></div>
        <div><label for="cny">Country:</label><input type="text" id="cny" name="cny" required></div>
        <button type="submit">Register</button>
    </form>
</body>
</html>
