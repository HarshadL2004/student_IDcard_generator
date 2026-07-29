<?php
session_start();
require 'connection.php';

// Get registration number and type of ID
$regNo = $_POST['deleteId'];
$typeOfId = $_POST['typeOfId']; // expected values: "Student", "Teacher", or "Staff"

$_SESSION['show'] = true;

// Determine the correct table and column
switch ($typeOfId) {
    case 'Student':
        $sql = "DELETE FROM `Student` WHERE `s_regNo` = '$regNo'";
        break;
    case 'Teacher':
        $sql = "DELETE FROM `Teacher` WHERE `t_regNo` = '$regNo'";
        break;
    case 'Staff':
        $sql = "DELETE FROM `Staff` WHERE `st_regNo` = '$regNo'";
        break;
    default:
        $_SESSION["result"] = "Invalid ID type specified.";
        header("Location: ../index.php");
        exit();
}

if (mysqli_query($conn, $sql)) {
    $_SESSION["result"] = "Record deleted successfully.";
} else {
    $_SESSION["result"] = "Could not delete record: " . mysqli_error($conn);
}

mysqli_close($conn);
header("Location: ../index.php");

?>
