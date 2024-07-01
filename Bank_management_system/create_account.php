
<?php

require 'connectdb.php';

if (isset($_POST['submit'])) {
 

    // Retrieve and escape form data
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // No need to escape as we will hash it
    $dob = $conn->real_escape_string($_POST['dob']);

    // Check if the email already exists
    $sql = "SELECT id FROM user_info WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email has already been taken');</script>";
        echo "<script>window.location.href = 'new_account.php';</script>";
    } else if (!preg_match("/@gmail\.com$/", $email)) {
        echo "<script>alert('Email must be a @gmail.com address');</script>";
        echo "<script>window.location.href = 'new_account.php';</script>";
    } else if (strlen($password) < 4) {
        echo "<script>alert('Password must be more than 3 characters');</script>";
        echo "<script>window.location.href = 'new_account.php';</script>";
    } else if (strlen($fullname) == 0) {
        echo "<script>alert('Full name cannot be empty');</script>";
        echo "<script>window.location.href = 'new_account.php';</script>";
    } else {
        
     
        // Insert the data into the user_info table
        $qry = "INSERT INTO user_info (full_name, address, phone, email, password, date_of_birth) VALUES ('$fullname', '$address', '$phone', '$email', '$password', '$dob')";
        $insrt = $conn->query($qry);

        if ($insrt) {
            echo "<script>alert('Account created successfully');</script>";
            echo "<script>window.location.href = 'log_in.php';</script>";
        } else {
            echo "<script>alert('Error occurred during record insertion');</script>";
            echo "<script>window.location.href = 'new_account.php';</script>";
        }
    }
} else {
    // Debug alert to check if the form submission is not detected
    echo "<script>alert('Form not submitted');</script>";
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Create Account</h2>
                <form id="createAccountForm" method="POST" action="create_account.php">
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter full name" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number" pattern="[0-9]{11}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

