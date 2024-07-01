<?php
session_start();
require 'connectdb.php';

if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Check if email and password match
    $sql = "SELECT * FROM user_info WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
     
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['full_name'];
            echo "<script>alert('Login successful');</script>";
            echo "<script>window.location.href = 'home.php';</script>"; 
      
    } else {
        echo "<script>alert('Invalid email or password');</script>";
        echo "<script>window.location.href = 'log_in.php';</script>";
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
                    <br><br>
                    <a href="forget.php" class="btn btn-primary">Forget password?</a>
                    <br><br>
                    <a href="create_account.php" class="btn btn-primary btn-lg mr-2">Create Account</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

