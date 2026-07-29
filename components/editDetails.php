<?php
// Prevent undefined variable issues
$userType = isset($userType) ? $userType : '';
$userData = isset($userData) ? $userData : [];

// Fetch departments for the dropdown
require 'database/connection.php';
$departmentsResult = $conn->query("SELECT DeptID, DeptName FROM Department");
$departments = $departmentsResult ? $departmentsResult->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editLabel">Edit <?php echo htmlspecialchars($userType); ?> Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="database/updateDetails.php" enctype="multipart/form-data">
                    <input type="hidden" name="userType" value="<?php echo htmlspecialchars($userType); ?>">
                    <input type="hidden" name="regNo" value="<?php echo htmlspecialchars($userData['regNo'] ?? ''); ?>">
                    <input type="hidden" name="currentImage" value="<?php echo htmlspecialchars($userData['image'] ?? ''); ?>">

                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($userData['firstName'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($userData['lastName'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo htmlspecialchars($userData['mobile'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="regNo" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="regNo" name="regNo" value="<?php echo htmlspecialchars($userData['regNo'] ?? ''); ?>" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept['DeptID']); ?>"
                                    <?php if (isset($userData['department']) && $userData['department'] == $dept['DeptID']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($dept['DeptName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($userData['dob'] ?? ''); ?>" required>
                    </div>

                    <?php if ($userType === 'Student'): ?>
                        <div class="mb-3">
                            <label for="courseStart" class="form-label">Course Start</label>
                            <input type="date" class="form-control" id="courseStart" name="courseStart" value="<?php echo htmlspecialchars($userData['courseStart'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="courseEnd" class="form-label">Course End</label>
                            <input type="date" class="form-control" id="courseEnd" name="courseEnd" value="<?php echo htmlspecialchars($userData['courseEnd'] ?? ''); ?>" required>
                        </div>
                    <?php elseif ($userType === 'Teacher' || $userType === 'Staff'): ?>
                        <div class="mb-3">
                            <label for="joiningDate" class="form-label">Joining Date</label>
                            <input type="date" class="form-control" id="joiningDate" name="joiningDate" value="<?php echo htmlspecialchars($userData['courseStart'] ?? $userData['joiningDate'] ?? ''); ?>" required>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($userData['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>

                    <?php if (!empty($userData['image'])): ?>
                        <div class="mb-3">
                            <label>Current Image:</label><br>
                            <?php
                            $imagePath = "images/";
                            if ($userType == 'Student') $imagePath .= "student_images/";
                            elseif ($userType == 'Teacher') $imagePath .= "teacher_images/";
                            elseif ($userType == 'Staff') $imagePath .= "staff_images/";
                            $imagePath .= htmlspecialchars($userData['image'] ?? '');
                            ?>
                            <img class="img-thumbnail" width="100px" src="<?php echo $imagePath; ?>" alt="Current Image">
                        </div>
                    <?php endif; ?>

                    <button type="submit" name="updateUser" class="btn btn-primary">Update</button>
                </form>
            </div>

        </div>
    </div>
</div>
<?php if (isset($conn)) $conn->close(); ?>