<?php
session_start();
require 'connectdb.php';

if (isset($_POST['submit'])) {
    $email = $conn->real_escape_string($_POST['email']);

    // Check if email exists
    $sql = "SELECT * FROM user_info WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $password = $row['password'];
        
        // Send email with the password
        $to = $email;
        $subject = "Your Password";
        $message = "Your password is: " . $password;
        $headers = "From: no-reply@example.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "<script>alert('Your password has been sent to your email.');</script>";
        } else {
            echo "<script>alert('Failed to send email. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forgot Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h3>Forgot Password</h3>
          </div>
          <div class="card-body">
            <form action="forget.php" method="post">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-center mt-3">
            <a href="log_in.php" class="text-decoration-none">Back to Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
