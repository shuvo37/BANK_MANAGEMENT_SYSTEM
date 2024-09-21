<?php
session_start();
require 'connectdb.php';

function showAlert($text, $icon, $redirect = null) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            text: '$text',
            icon: '$icon',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {";
    if ($redirect) {
        echo "window.location.href = '$redirect';";
    }
    echo "}
        });
    });
    </script>";
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidPassword($password) {
    // Add your password validity criteria here (e.g., length, special characters, etc.)
    return strlen($password) >= 4;
}

if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    if (!isValidEmail($email)) {
        showAlert('Invalid email format', 'error', 'log_in.php');
    } elseif (!isValidPassword($password)) {
        showAlert('Password must be at least 8 characters long', 'error', 'log_in.php');
    } else {
        // Check if email and password match
        $sql = "SELECT * FROM user_info WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verify password if it's hashed (use password_verify function)
            // if (password_verify($password, $row['password'])) {
            if ($password === $row['password']) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_branch'] = $row['branch'];
                $_SESSION['user_name'] = $row['full_name'];

                showAlert('Login successful', 'success', 'home.php');
            } else {
                showAlert('Invalid email or password', 'error', 'log_in.php');
            }
        } else {
            showAlert('Invalid email or password', 'error', 'log_in.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Management Project</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            background-image: url('images/lg1.jpg'); /* Replace with your image path */
            background-size: cover; /* Ensure the image covers the whole background */
            background-position: center; /* Center the background image */
            height: 100vh; /* Full height */
        }
        .container {
            background: rgba(255, 255, 255, 0.8); /* White background with transparency */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .btn {
            width: 100%; /* Make buttons full width */
            margin-bottom: 10px; /* Space between buttons */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h2>Bank Management System</h2>
                <form action="log_in.php" method="post">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="login">Login</button>
                    <a href="forget.php" class="btn btn-primary">Forget password?</a>
                    <a href="create_account.php" class="btn btn-primary">Create Account</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>