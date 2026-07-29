<?php
session_start();
require 'connection.php';

if (isset($_POST['updateUser'])) { // Changed from 'addDetails' to 'updateUser' to match the button name

    $currentDirectory = rtrim(getcwd(), "/database");
    $uploadDirectory = "";
    $userType = $_POST['userType']; // Get user type to determine the image directory

    if ($userType === "Student") {
        $uploadDirectory = "/images/student_images/";
    } elseif ($userType === "Teacher") {
        $uploadDirectory = "/images/teacher_images/";
    } elseif ($userType === "Staff") {
        $uploadDirectory = "/images/staff_images/";
    }

    $fileName = $_FILES['image']['name']; // Changed from 'uploadFile' to 'image'
    $fileTmpName   = $_FILES['image']['tmp_name']; // Changed from 'uploadFile' to 'image'
    $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName);

    if ($fileName == NULL) {
        $fileName = $_POST['currentImage']; // Assuming you'll add a hidden field for the current image name
    }

    if (!isset($errors) || empty($errors)) {
        if (!empty($_FILES['image']['name'])) { // Only upload if a new image is selected
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
            if (!$didUpload) {
                echo "An error occurred during image upload.";
            }
        }
    }

    // Collect form data - Using the same names as in editDetails.php
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $reg = $_POST['regNo'];
    $email   = $_POST['email'];
    $mobile   = $_POST['mobile'];
    $dob   = $_POST['dob'];
    $address   = $_POST['address'];
    $deptID = $_POST['department']; // Changed from deptID to department to match editDetails.php

    $image   = $fileName; // Will be the new filename or the old one if no new image was uploaded

    // Determine which table and fields to update
    $table = '';
    $fields = '';

    if ($userType === "Student") {
        $table = "Student";
        $fields = "`s_firstName` = '$fname', `s_lastName` = '$lname', `s_email` = '$email',
                   `s_mobile` = '$mobile', `s_dob` = '$dob', `s_courseStart` = '{$_POST['courseStart']}',
                   `s_courseEnd` = '{$_POST['courseEnd']}', `s_image` = '$image', `s_address` = '$address',
                   `s_DeptID` = '$deptID'";
        $sql = "UPDATE `$table` SET $fields WHERE `s_regNo` = '$reg'";
    } elseif ($userType === "Teacher") {
        $table = "Teacher";
        $fields = "`t_firstName` = '$fname', `t_lastName` = '$lname', `t_email` = '$email',
                   `t_mobile` = '$mobile', `t_dob` = '$dob', `t_joiningDate` = '{$_POST['joiningDate']}',
                   `t_image` = '$image', `t_address` = '$address', `t_DeptID` = '$deptID'";
        $sql = "UPDATE `$table` SET $fields WHERE `t_regNo` = '$reg'";
    } elseif ($userType === "Staff") {
        $table = "Staff";
        $fields = "`st_firstName` = '$fname', `st_lastName` = '$lname', `st_email` = '$email',
                   `st_mobile` = '$mobile', `st_dob` = '$dob', `st_joiningDate` = '{$_POST['joiningDate']}',
                   `st_image` = '$image', `st_address` = '$address', `st_DeptID` = '$deptID'";
        $sql = "UPDATE `$table` SET $fields WHERE `st_regNo` = '$reg'";
    } else {
        $_SESSION["result"] = "Invalid user type!";
        header("Location: ../index.php");
        exit();
    }

    $_SESSION['show'] = true;

    if (mysqli_query($conn, $sql)) {
        $_SESSION["result"] = "$userType record updated successfully.";
    } else {
        $_SESSION["result"] = "Error updating $userType: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: ../index.php");
}
?>