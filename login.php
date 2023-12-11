<?php
session_start(); // Start session (if not already started)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orbit | SignIn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <form method="post">
        <label for="email">Email</label>
        <input type="text" name="email">
        <label for="email">Password</label>
        <input type="password" name="password">
        <input type="submit" name="login" value="Log In">
    </form>
    <a href="signup.php">Create Account</a>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'database/config.php'; // Include database configuration

    if (empty($_POST['email']) || empty($_POST['password'])) {
        // Error handling for incomplete form
        echo "<script>Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Incomplete Form!',
            showConfirmButton: false,
        });</script>";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            // Check user credentials using email
            $userCheck = $conn->prepare("SELECT userID, Email, Password, Role FROM user WHERE Email = ?");
            $userCheck->bind_param("s", $email);
            $userCheck->execute();
            $userResult = $userCheck->get_result();

            if ($userResult->num_rows == 1) {
                // User found, verify password
                $user = $userResult->fetch_assoc();
                if (password_verify($password, $user['Password'])) {
                    // Password is correct, set session and redirect based on role
                    $_SESSION['userID'] = $user['userID'];
                    $_SESSION['email'] = $user['Email'];

                    if ($user['Role'] === 'admin') {
                        // Redirect admin to admin dashboard
                        echo "<script>
                            window.location.href = 'admin/dashboard.php'; 
                        </script>";
                    } else {
                        // Redirect regular users to member dashboard or desired page
                        echo "<script>
                            window.location.href = 'member/dashboard.php'; 
                        </script>";
                    }
                    exit();
                } else {
                    // Incorrect password error handling
                    echo "<script>Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Incorrect Password!',
                        showConfirmButton: false,
                    });</script>";
                }
            } else {
                // User not found error handling
                echo "<script>Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'User not Found!',
                    showConfirmButton: false,
                });</script>";
            }
        } catch (Exception $e) {
            // Handle exceptions
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred during login. Please try again later.',
                showConfirmButton: false
            });</script>";
        } finally {
            $conn->close(); // Close the database connection
        }
    }
}
?>