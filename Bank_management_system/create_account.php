<?php
require 'connectdb.php';

function showAlert($text , $icon, $redirect = null) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '$icon',
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

function calculateAge($dob) {
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;
    return $age;
}

if (isset($_POST['submit'])) {
    $branch = isset($_POST['branch']) ? $_POST['branch'] : '';

    // Retrieve and escape form data
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $dob = $conn->real_escape_string($_POST['dob']);

    // Check if the email already exists
    $sql = "SELECT id FROM user_info WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        showAlert('Email has already been taken', 'error');
    } else if (!preg_match("/@gmail\.com$/", $email)) {
        showAlert('Email must be a @gmail.com address', 'error');
    } else if (strlen($password) < 4) {
        showAlert('Password must be more than 3 characters', 'error');
    } else if (strlen($fullname) == 0) {
        showAlert('Full name cannot be empty', 'error');
    } else if (strlen($branch) == 0) {
        showAlert('Branch did not select', 'error');
    } else if (calculateAge($dob) < 18) {
        showAlert('You must be at least 18 years old', 'error');
    } else {
        // Insert the data into the user_info table
        $qry = "INSERT INTO user_info (full_name, address, phone, email, password, date_of_birth, branch)
                VALUES ('$fullname', '$address', '$phone', '$email', '$password', '$dob', '$branch')";
        $insrt = $conn->query($qry);

        if ($insrt) {
            showAlert('Account created successfully', 'success', 'log_in.php');
        } else {
            showAlert('Error occurred during record insertion', 'error');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: url(images/bank2.jpg);
            color: white;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #333;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .navbar-brand:hover, .nav-link:hover {
            color: #ffcbcb !important;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: transparent;
            border-bottom: none;
            color: #fff;
            font-weight: bold;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            color: #333;
        }

        .btn-primary {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .btn-primary:hover {
            background-color: #ff5733;
            border-color: #ff5733;
        }

        .dropdown-toggle {
            background-color: #ff7f50;
            border-color: #ff7f50;
        }

        .dropdown-menu {
            background-color: #333;
        }

        .dropdown-item {
            color: white;
        }

        .dropdown-item:hover {
            background-color: #ff5733;
        }
    </style>

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
                    <div class="form-group text-center">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle w-100" type="button" id="branchDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Select Branch
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="branchDropdown">
                                <li><a class="dropdown-item" href="#" onclick="selectBranch('Branch_A')">Branch_A</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectBranch('Branch_B')">Branch_B</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectBranch('Branch_C')">Branch_C</a></li>
                            </ul>
                        </div>
                    </div>
                    <br><br>
                    <input type="hidden" name="branch" id="branchInput" value="">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function selectBranch(branch) {
        document.getElementById('branchInput').value = branch;
        document.getElementById('branchDropdown').innerText = branch;
    }

    document.getElementById('createAccountForm').addEventListener('submit', function(event) {
        var dobInput = document.getElementById('dob').value;
        var dob = new Date(dobInput);
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var month = today.getMonth() - dob.getMonth();
        if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        if (age < 18) {
            event.preventDefault();
            Swal.fire({
                title: 'Error',
                text: 'You must be at least 18 years old',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
    </script>
</body>
</html>
