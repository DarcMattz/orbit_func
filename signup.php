<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orbit | SignUp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <form method="post">
        <label for="email">Username</label>
        <input type="text" name="username">
        <label for="email">Email</label>
        <input type="text" name="email">
        <label for="email">Password</label>
        <input type="password" name="password">
        <input type="submit" name="register" value="Sign Up">
    </form>
    <a href="login.php">Login</a>
</body>

</html>

<?php
require 'database/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        // Error handling for incomplete form
        echo "<script>Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Incomplete Form!',
            showConfirmButton: false,
        });</script>";
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            // Check if the username already exists
            $userCheckUsername = $conn->prepare("SELECT username FROM user WHERE username = ?");
            $userCheckUsername->bind_param("s", $username);
            $userCheckUsername->execute();
            $usernameCount = $userCheckUsername->get_result();

            // Check if the email already exists
            $userCheckEmail = $conn->prepare("SELECT email FROM user WHERE email = ?");
            $userCheckEmail->bind_param("s", $email);
            $userCheckEmail->execute();
            $emailCount = $userCheckEmail->get_result();

            if ($usernameCount->num_rows > 0) {
                // Username already exists error handling
                echo "<script>Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Username $username already exists!',
                    showConfirmButton: false
                });</script>";
            } elseif ($emailCount->num_rows > 0) {
                // Email already exists error handling
                echo "<script>Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'the Email $email has already been used!',
                    showConfirmButton: false
                });</script>";
            } else {
                // Insert new user if the username doesn't exist
                $role = 'member'; // Define the role
                $insertQuery = $conn->prepare("INSERT INTO user (Username, Password, Email, Role) VALUES (?, ?, ?, ?)");
                $insertQuery->bind_param("ssss", $username, $password, $email, $role);


                if ($insertQuery->execute()) {
                    echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Registration successful. You can now login.',
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'login.php'; // Redirect to login page
                    });</script>";
                } else {
                    // Registration failed error handling
                    echo "<script>Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Registration Failed!',
                        showConfirmButton: false
                    });</script>";
                }
            }
        } catch (Exception $e) {
            // Handle exceptions
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred during registration. Please try again later.',
                showConfirmButton: false
            });</script>";
        } finally {
            $conn->close(); // Close the database connection
        }
    }
}
?>