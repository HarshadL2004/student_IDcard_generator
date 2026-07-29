<div class="modal" id="downloadCard">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Id Card</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div id="card" class="modal-body">
                <section id="img" class="id-card container">
                    <div class="head">
                        <div><img class="img-thumbnail" src="images/icons/lpu.png" alt="logo"></div>
                        <div>
                            <p class="mb-0">Lovely Professional University</p>
                        </div>
                    </div>
                    <div class="body">
                        <div class="image">
                            <img class="img-thumbnail" src="images/<?php
                                if (isset($dataa['image'])) {
                                    if ($_POST['downloadType'] === 'Student') {
                                        echo 'student_images/' . $dataa['image'];
                                    } elseif ($_POST['downloadType'] === 'Teacher' || $_POST['downloadType'] === 'Staff') {
                                        echo 'employee_images/' . $dataa['image']; // Assuming you have a separate folder for employee images
                                    } else {
                                        echo 'default.png'; // Or some default image
                                    }
                                } else {
                                    echo 'default.png'; // Or some default image if no image is set
                                }
                                ?>" alt="ID Card Photo">
                            <?php if (isset($dataa['dob'])): ?>
                                <p class="dob"><b>DOB:</b> <?php echo $dataa['dob']; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="details">
                            <p><b>Name:</b> <?php echo $dataa['firstName'] . ' ' . $dataa['lastName']; ?></p>
                            <p><b>Reg No:</b> <?php echo $dataa['regNo']; ?></p>
                            <?php if (isset($dataa['mobile'])): ?>
                                <p><b>Mobile:</b> <?php echo $dataa['mobile']; ?></p>
                            <?php endif; ?>

                            <?php if ($_POST['downloadType'] === 'Student' && isset($dataa['courseEnd'])): ?>
                                <p><b>Valid Until:</b> <?php echo $dataa['courseEnd']; ?></p>
                            <?php elseif ($_POST['downloadType'] === 'Teacher' && isset($dataa['designation'])): ?>
                                <p><b>Designation:</b> <?php echo $dataa['designation']; ?></p>
                            <?php elseif ($_POST['downloadType'] === 'Staff' && isset($dataa['role'])): ?>
                                <p><b>Role:</b> <?php echo $dataa['role']; ?></p>
                            <?php endif; ?>

                            <?php if (isset($dataa['department'])): ?>
                                <p><b>Department:</b> <?php echo $dataa['department']; ?></p>
                            <?php endif; ?>

                            <?php if (isset($dataa['joinEnd'])): ?>
                                <p><b>Valid Until:</b> <?php echo $dataa['joinEnd']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <div id="showLink" class="alert alert-success text-center alert-dismissible mt-4 fade show">
                    <button type="button" class="btn-close" onclick="$('#showLink').hide();"></button>
                    <a class="text-black" id="download" href="" target="_blank">Download</a>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" onclick="capture()" class="btn btn-primary">Generate Link</button>
            </div>

        </div>
    </div>
</div>