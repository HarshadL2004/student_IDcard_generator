<?php
session_start();
if (!isset($_SESSION["logged"])) {
    header('Location: login.php');
    exit;
}
require './database/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <?php require 'components/headContent.php'; ?>
    <link rel="stylesheet" href="styles/index.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.4/html2canvas.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar justify-content-between px-5">
        <div class="navbar-brand"><p class="mb-0">Dashboard</p></div>
        <form action="database/logout.php" method="POST">
            <button type="submit">Log Out</button>
        </form>
    </nav>
</header>

<?php if (isset($_SESSION["message"])): ?>
    <div class="alert alert-success text-center"><?= $_SESSION["user"]; ?> logged in.</div>
    <?php unset($_SESSION["message"]); ?>
<?php endif; ?>

<?php if (isset($_SESSION["show"])): ?>
    <div class="alert alert-info text-center"><?= $_SESSION["result"]; ?></div>
    <?php unset($_SESSION["show"]); ?>
<?php endif; ?>

<main class="container mx-auto mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>User Details</h2>
        <button class="btn" data-bs-toggle="modal" data-bs-target="#addStudent">
            <i class="bi bi-plus-circle" style="font-size: 1.5rem;"></i>
        </button>
    </div>
    <?php require 'components/addStudentModal.php'; ?>

    <table class="table table-hover details-table">
        <thead>
        <tr>
            <th>S No.</th>
            <th>Name</th>
            <th>Registration</th>
            <th>Department</th> <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $allData = [];
        $queries = [
            'Student' => "SELECT s.s_firstName AS firstName, s.s_lastName AS lastName, s.s_regNo AS regNo, d.DeptName AS department FROM Student s JOIN Department d ON s.s_DeptID = d.DeptID",
            'Teacher' => "SELECT t.t_firstName AS firstName, t.t_lastName AS lastName, t.t_regNo AS regNo, d.DeptName AS department FROM Teacher t JOIN Department d ON t.t_DeptID = d.DeptID",
            'Staff'   => "SELECT st.st_firstName AS firstName, st.st_lastName AS lastName, st.st_regNo AS regNo, d.DeptName AS department FROM Staff st JOIN Department d ON st.st_DeptID = d.DeptID"
        ];
        foreach ($queries as $type => $query) {
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                $row['type'] = $type;
                $allData[] = $row;
            }
        }

        $sno = 1;
        foreach ($allData as $row):
        ?>
        <tr>
            <td class="text-center"><?= $sno++; ?></td>
            <td><?= $row['firstName'] . ' ' . $row['lastName']; ?> <small class="text-muted">(<?= $row['type']; ?>)</small></td>
            <td><?= $row['regNo']; ?></td>
            <td><?= $row['department']; ?></td>
            <td>
                <div class="d-flex justify-content-center gap-2">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="editId" value="<?= $row['regNo']; ?>">
                        <input type="hidden" name="editType" value="<?= $row['type']; ?>">
                        <button type="submit" name="edit" class="btn p-0"><i class="bi bi-pencil"></i></button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="downloadId" value="<?= $row['regNo']; ?>">
                        <input type="hidden" name="downloadType" value="<?= $row['type']; ?>">
                        <button type="submit" class="btn p-0"><i class="bi bi-download"></i></button>
                    </form>
                    <form action="database/deleteDetails.php" method="POST" style="display:inline;">
                        <input type="hidden" name="deleteId" value="<?= $row['regNo']; ?>">
                        <input type="hidden" name="typeOfId" value="<?= $row['type']; ?>">
                        <button type="submit" class="btn p-0"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php
    if (isset($_POST['edit'])) {
        $regNo = $_POST['editId'];
        $type = $_POST['editType'];

        $userData = [];
        $query = "";
        switch ($type) {
            case "Student":
                $query = "SELECT s.*, d.DeptID AS department FROM Student s LEFT JOIN Department d ON s.s_DeptID = d.DeptID WHERE s.s_regNo = '$regNo'";
                break;
            case "Teacher":
                $query = "SELECT t.*, d.DeptID AS department FROM Teacher t LEFT JOIN Department d ON t.t_DeptID = d.DeptID WHERE t.t_regNo = '$regNo'";
                break;
            case "Staff":
                $query = "SELECT st.*, d.DeptID AS department FROM Staff st LEFT JOIN Department d ON st.st_DeptID = d.DeptID WHERE st.st_regNo = '$regNo'";
                break;
        }

        $res = mysqli_query($conn, $query);
        if ($res && mysqli_num_rows($res) > 0) {
            $data = mysqli_fetch_assoc($res);

            if ($type == "Student") {
                $userData = [
                    'firstName' => $data['s_firstName'] ?? '',
                    'lastName' => $data['s_lastName'] ?? '',
                    'email' => $data['s_email'] ?? '',
                    'mobile' => $data['s_mobile'] ?? '',
                    'regNo' => $data['s_regNo'] ?? '',
                    'dob' => $data['s_dob'] ?? '',
                    'courseStart' => $data['s_courseStart'] ?? '',
                    'courseEnd' => $data['s_courseEnd'] ?? '',
                    'address' => $data['s_address'] ?? '',
                    'image' => $data['s_image'] ?? '',
                    'department' => $data['department'] ?? ''
                ];
            } elseif ($type == "Teacher") {
                $userData = [
                    'firstName' => $data['t_firstName'] ?? '',
                    'lastName' => $data['t_lastName'] ?? '',
                    'email' => $data['t_email'] ?? '',
                    'mobile' => $data['t_mobile'] ?? '',
                    'regNo' => $data['t_regNo'] ?? '',
                    'dob' => $data['t_dob'] ?? '',
                    'courseStart' => $data['t_joiningDate'] ?? '',
                    'courseEnd' => '', // Not applicable
                    'address' => $data['t_address'] ?? '',
                    'image' => $data['t_image'] ?? '',
                    'department' => $data['department'] ?? ''
                ];
            } elseif ($type == "Staff") {
                $userData = [
                    'firstName' => $data['st_firstName'] ?? '',
                    'lastName' => $data['st_lastName'] ?? '',
                    'email' => $data['st_email'] ?? '',
                    'mobile' => $data['st_mobile'] ?? '',
                    'regNo' => $data['st_regNo'] ?? '',
                    'dob' => $data['st_dob'] ?? '',
                    'courseStart' => $data['st_joiningDate'] ?? '',
                    'courseEnd' => '', // Not applicable
                    'address' => $data['st_address'] ?? '',
                    'image' => $data['st_image'] ?? '',
                    'department' => $data['department'] ?? ''
                ];
            }

            $userType = $type;
            include "components/editDetails.php";
            echo "<script>var myModal = new bootstrap.Modal(document.getElementById('edit')); myModal.show();</script>";
        }
    }
    ?>

<?php

// Handle Download Modal
if (isset($_POST['downloadType'], $_POST['downloadId'])) {
    $type = $_POST['downloadType'];
    $reg = $_POST['downloadId'];

    $sql = match($type) {
        'Student' => "SELECT s.s_firstName AS firstName, s.s_lastName AS lastName, s.s_regNo AS regNo, s.s_mobile AS mobile, s.s_dob AS dob, s.s_image AS image, d.DeptName AS department FROM Student s JOIN Department d ON s.s_DeptID = d.DeptID WHERE s.s_regNo = '$reg'",
        'Teacher' => "SELECT t.t_firstName AS firstName, t.t_lastName AS lastName, t.t_regNo AS regNo, t.t_mobile AS mobile, t.t_dob AS dob, t.t_image AS image, d.DeptName AS department FROM Teacher t JOIN Department d ON t.t_DeptID = d.DeptID WHERE t.t_regNo = '$reg'",
        'Staff'   => "SELECT st.st_firstName AS firstName, st.st_lastName AS lastName, st.st_regNo AS regNo, st.st_mobile AS mobile, st.st_dob AS dob, st.st_image AS image, d.DeptName AS department FROM Staff st JOIN Department d ON st.st_DeptID = d.DeptID WHERE st.st_regNo = '$reg'",
        default   => ''
    };

    if ($sql) {
        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $dataa = $res->fetch_assoc();
            require 'components/downloadCard.php';
            echo "<script>document.addEventListener('DOMContentLoaded', function() { $('#downloadCard').modal('show'); });</script>";
        } else {
            echo "<div class='alert alert-warning text-center'>No matching record found.</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid user type selected.</div>";
    }
}
?>

<script>
    function capture() {
        html2canvas(document.getElementById("img")).then(canvas => {
            const downloadLink = document.getElementById("download");
            downloadLink.download = "id-card.png";
            downloadLink.href = canvas.toDataURL("image/png");
            document.getElementById("showLink").style.display = 'block';
        });
    }

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</body>
</html>