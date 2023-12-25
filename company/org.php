<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organization Management</title>
    <!-- Include AdminLTE CSS files here -->
    <!-- Example: <link rel="stylesheet" href="path/to/adminlte.min.css"> -->
</head>
<body>
    <h2>Organization Form</h2>
    <form action="crud.php" method="post">
        <label for="org_code">Organization Code:</label><br>
        <input type="text" id="org_code" name="org_code" required><br>

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="contactname">Contact Name:</label><br>
        <input type="text" id="contactname" name="contactname"><br>

        <label for="contactposition">Contact Position:</label><br>
        <input type="text" id="contactposition" name="contactposition"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="website">Website:</label><br>
        <input type="url" id="website" name="website"><br>

        <label for="phone1">Phone 1:</label><br>
        <input type="tel" id="phone1" name="phone1"><br>

        <label for="phone2">Phone 2:</label><br>
        <input type="tel" id="phone2" name="phone2"><br>

        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address"><br><br>

        <input type="submit" name="action" value="Create">
        <input type="submit" name="action" value="Read">
        <input type="submit" name="action" value="Update">
        <input type="submit" name="action" value="Delete">
    </form>
</body>
</html>
