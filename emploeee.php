<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dir";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    $employee_ssn = $_POST["employee_ssn"];
    $name = $_POST["name"];
    $salary = $_POST["salary"];
    $department_id = $_POST["department_id"];

    // Insert data into the employees table
    $sql = "INSERT INTO employees (employee_ssn, name, salary, department_id) VALUES ('$employee_ssn', '$name', '$salary', '$department_id')";

    if (mysqli_query($conn, $sql)) {
        echo "Employee created successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// READ operation - Fetch all employees or searched employees
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $searchTerm = $_POST["searchTerm"];
    // Search for employees whose names contain the search term
    $sqlSearch = "SELECT employees.id, employee_ssn, employees.name, salary, departments.name AS department_name, location
                  FROM employees
                  LEFT JOIN departments ON employees.department_id = departments.id
                  WHERE employees.name LIKE '%$searchTerm%'";
    $resultEmployees = mysqli_query($conn, $sqlSearch);
} else {
    // Fetch all employees
    $sqlFetchEmployees = "SELECT employees.id, employee_ssn, employees.name, salary, departments.name AS department_name, location
                          FROM employees
                          LEFT JOIN departments ON employees.department_id = departments.id";
    $resultEmployees = mysqli_query($conn, $sqlFetchEmployees);
}

// UPDATE operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = $_POST["id"];
    $salary = $_POST["salary"];
    $department_id = $_POST["department_id"];

    // Update data in the employees table
    $sql = "UPDATE employees SET salary='$salary', department_id='$department_id' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Employee updated successfully!";
        echo "<script>setTimeout(function(){ location.reload(); }, 100);</script>";

    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// DELETE operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = $_POST["id"];

    // Delete data from the employees table
    $sql = "DELETE FROM employees WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Employee deleted successfully!";
        echo "<script>setTimeout(function(){ location.reload(); }, 100);</script>";

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
    <title>Employee Management</title>
</head>
<body>

<h2>Add Employee</h2>
<form method="post">
    <label for="employeeSSN">Employee SSN:</label>
    <input type="text" id="employeeSSN" name="employee_ssn" required><br>

    <label for="employeeName">Employee Name:</label>
    <input type="text" id="employeeName" name="name" required><br>

    <label for="employeeSalary">Employee Salary:</label>
    <input type="text" id="employeeSalary" name="salary" required><br>

    <label for="employeeDepartment">Employee Department ID:</label>
    <input type="text" id="employeeDepartment" name="department_id" required><br>

    <input type="submit" name="create" value="Add Employee">
</form>

<h2>Search Employee</h2>
<form method="post">
    <label for="searchTerm">Search by Employee Name:</label>
    <input type="text" id="searchTerm" name="searchTerm" required>
    <input type="submit" name="search" value="Search">
</form>

<h2>Employee List</h2>
<table border="1" style="border-collapse: collapse; width: 100%;">
    <tr>
        <th>ID</th>
        <th>Employee SSN</th>
        <th>Name</th>
        <th>Salary</th>
        <th>Department Name</th>
        <th>Location</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>
    <?php
    // Display all employees or searched employees
    if (isset($resultEmployees) && $resultEmployees->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($resultEmployees)) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["employee_ssn"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["salary"] . "</td>";
            echo "<td>" . $row["department_name"] . "</td>";
            echo "<td>" . $row["location"] . "</td>";

            // Update form
            echo "<td>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
            echo "<label for='updateSalary'>New Salary:</label>";
            echo "<input type='text' id='updateSalary' name='salary' required>";
            echo "<label for='updateDepartment'>New Department ID:</label>";
            echo "<input type='text' id='updateDepartment' name='department_id' required>";
            echo "<input type='submit' name='update' value='Update'>";
            echo "</form>";
            echo "</td>";

            // Delete button
            echo "<td>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
            echo "<input type='submit' name='delete' value='Delete'>";
            echo "</form>";
            echo "</td>";

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No employees found.</td></tr>";
    }
    ?>
</table>

</body>
</html>

