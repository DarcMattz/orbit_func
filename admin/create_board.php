<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <h1>Create Project</h1>
        <label for="cover">Cover Page</label>
        <input type="file" name="cover" id="cover" accept=".jpg, .jpeg, .png">
        <br>
        <label for="title">Title</label>
        <input type="text" name="title" id="title"><br>
        <input type="submit" value="Save" name="save">
    </form>
</body>

</html>

<?php
require '../database/config.php';

if (isset($_POST['save'])) {
    if (!empty($_POST['title']) && !empty($_FILES['cover']['name'])) {
        $title = $_POST['title'];
        $fileExtension = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
        $uniqueFilename = $title . '.' . $fileExtension;
        $targetDir = "../uploads/";
        $targetFile = $targetDir . $uniqueFilename;

        $validExtensions = array("jpg", "jpeg", "png");
        if (in_array($fileExtension, $validExtensions)) {
            // Check if title already exists
            $checkTitleQuery = $conn->prepare("SELECT COUNT(*) FROM project WHERE projectName = ?");
            $checkTitleQuery->bind_param("s", $title);
            $checkTitleQuery->execute();
            $checkTitleQuery->bind_result($titleCount);
            $checkTitleQuery->fetch();
            $checkTitleQuery->close();

            if ($titleCount > 0) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Title already exists!',
                        showConfirmButton: false
                    });
                </script>";
            } else {
                if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetFile)) {
                    $insertQuery = $conn->prepare("INSERT INTO project (projectName, projectCover) VALUES (?, ?)");
                    $insertQuery->bind_param("ss", $title, $targetFile);

                    if ($insertQuery->execute()) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Project created successfully.',
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = 'dashboard.php'; // Redirect to dashboard page
                            });
                        </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to save project details!',
                                showConfirmButton: false
                            });
                        </script>";
                    }
                    $insertQuery->close();
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error uploading file.',
                            showConfirmButton: false
                        });
                    </script>";
                }
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Invalid file format. Please upload an image file.',
                    showConfirmButton: false
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Fill in all the fields!',
                showConfirmButton: false
            });
        </script>";
    }
}
?>