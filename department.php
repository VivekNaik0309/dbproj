<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dir";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// CREATE operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    $name = $_POST["name"];
    $location = $_POST["location"];

    // Insert data into the departments table
    $sql = "INSERT INTO departments (name, location) VALUES ('$name', '$location')";

    if (mysqli_query($conn, $sql)) {
        echo "Department created successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// READ operation - Fetch all departments
$resultDepartments = mysqli_query($conn, "SELECT * FROM departments");

// UPDATE operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $location = $_POST["location"];

    // Update data in the departments table
    $sql = "UPDATE departments SET name='$name', location='$location' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Department updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Departments Page</title>
</head>
<body>

<h2>Create Department</h2>
<form method="post">
    <label for="departmentName">Department Name:</label>
    <input type="text" id="departmentName" name="name" required><br>

    <label for="departmentLocation">Department Location:</label>
    <input type="text" id="departmentLocation" name="location" required><br>

    <input type="submit" name="create" value="Create Department">
</form>

<h2>Update Department</h2>
<form method="post">
    <label for="updateDepartmentId">Department ID to Update:</label>
    <input type="text" id="updateDepartmentId" name="id" required><br>

    <label for="updateDepartmentName">New Department Name:</label>
    <input type="text" id="updateDepartmentName" name="name" required><br>

    <label for="updateDepartmentLocation">New Department Location:</label>
    <input type="text" id="updateDepartmentLocation" name="location" required><br>

    <input type="submit" name="update" value="Update Department">
</form>

<h2>All Departments</h2>
<table border="1" style="border-collapse: collapse; width: 100%;">
    <tr>
        <th>ID</th>
        <th>Department Name</th>
        <th >Department Location</th>
    </tr>
    <?php
    // Display all departments
    if (mysqli_num_rows($resultDepartments) > 0) {
        while ($row = mysqli_fetch_assoc($resultDepartments)) {
            echo "<tr>";
            echo "<td >" . $row["id"] . "</td>";
            echo "<td >" . $row["name"] . "</td>";
            echo "<td >" . $row["location"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No departments found.</td></tr>";
    }
    ?>
</table>

</body>
</html>
