<?php
session_start();
require 'connection.php';

if (isset($_POST['addDetails'])) {

    $currentDirectory = rtrim(getcwd(), "/database");
    $uploadDirectory = "/images/student_images/";
    $fileName = $_FILES['uploadFile']['name'];
    $fileTmpName  = $_FILES['uploadFile']['tmp_name'];

    if ($fileName == NULL) {
        $fileName = 'userDp.png';
    }

    $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName);

    if (move_uploaded_file($fileTmpName, $uploadPath)) {
        echo "The file " . basename($fileName) . " has been uploaded";
    } else {
        echo "An error occurred during file upload.";
    }

    // Collect form inputs
    $typeOfId = $_POST['typeOfId'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $reg = $_POST['reg'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $dob = $_POST['dob'];
    $courseStart = $_POST['courseStart'];
    $courseEnd = $_POST['courseEnd'];
    $image = $fileName;
    $address = $_POST['address'];
    $deptName = $_POST['department'];

    // Get Department ID
    $deptQuery = "SELECT DeptID FROM Department WHERE DeptName = ?";
    $stmt = $conn->prepare($deptQuery);
    $stmt->bind_param("s", $deptName);
    $stmt->execute();
    $stmt->bind_result($deptId);
    $stmt->fetch();
    $stmt->close();

    if (!$deptId) {
        $_SESSION['result'] = "Department not found. Cannot generate ID card.";
        header("Location: ../index.php");
        exit();
    }

    // Insert into corresponding table
    if ($typeOfId === "Student") {
        $sql = "INSERT INTO Student (s_firstName, s_lastName, s_regNo, s_email, s_mobile, s_dob, s_courseStart, s_courseEnd, s_image, s_address, s_DeptID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssi", $fname, $lname, $reg, $email, $mobile, $dob, $courseStart, $courseEnd, $image, $address, $deptId);

    } elseif ($typeOfId === "Teacher") {
        $sql = "INSERT INTO Teacher (t_firstName, t_lastName, t_regNo, t_email, t_mobile, t_dob, t_joiningDate, t_image, t_address, t_DeptID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi", $fname, $lname, $reg, $email, $mobile, $dob, $courseStart, $image, $address, $deptId);

    } elseif ($typeOfId === "Staff") {
        $sql = "INSERT INTO Staff (st_firstName, st_lastName, st_regNo, st_email, st_mobile, st_dob, st_joiningDate, st_image, st_address, st_DeptID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssi", $fname, $lname, $reg, $email, $mobile, $dob, $courseStart, $image, $address, $deptId);

    } else {
        $_SESSION['result'] = "Invalid Type of ID selected.";
        header("Location: ../index.php");
        exit();
    }

    if ($stmt->execute()) {
        $_SESSION['result'] = "Record added and ID card generated successfully!";
    } else {
        $_SESSION['result'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    $_SESSION['show'] = true;
    header("Location: ../index.php");
}
?>
