<?php
require 'connectdb.php';
session_start();

function showAlert($text, $icon, $redirect = null) {
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

if (isset($_POST['submit'])) {
    $branch = isset($_POST['branch']) ? $_POST['branch'] : '';
    $amount = $_POST['amount'];
    $branch1 = $_SESSION['user_branch'];
    $id = $_SESSION['user_id'];
    $email = $_SESSION['user_email'];

    date_default_timezone_set('Asia/Dhaka');
    $dob = date('Y-m-d H:i:s');

    if ($branch == $branch1) {
        $sql = "SELECT * FROM saving WHERE account_id = '$id' AND branch_id = '$branch'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows == 0) {
                // Insert new record
                $qry = "INSERT INTO saving (account_id, total_saving, branch_id) 
                        VALUES ('$id', '$amount', '$branch')";
                $insrt = $conn->query($qry);

                if ($insrt) {
                    showAlert('Account saving successfully', 'success', 'saving.php');
                } else {
                    showAlert('Error occurred during record insertion: ' . $conn->error, 'error', 'saving.php');
                }

                $type = "saving";
                $qry1 = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch,amount)
                        VALUES ('$id', '$type', '$email', '$dob', '$branch','$amount')";
                $insrtTransfer = $conn->query($qry1);

            } else {
                // Update existing record
                $updateQry = "UPDATE saving 
                              SET total_saving = total_saving + $amount 
                              WHERE account_id = '$id' AND branch_id = '$branch'";
                $upd = $conn->query($updateQry);

                if ($upd) {
                    showAlert('Account saving successfully', 'success', 'saving.php');
                } else {
                    showAlert('Error occurred during record update: ' . $conn->error, 'error', 'saving.php');
                }

                $type = "saving";
                $qry = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch,amount)
                        VALUES ('$id', '$type', '$email', '$dob', '$branch','$amount')";
                $insrtTransfer = $conn->query($qry);


            }

           

            // if ($insrtTransfer) {
            //     showAlert('Saved in transfer successfully', 'success', 'saving.php');
            // } else {
            //     showAlert('Error occurred during record insertion in transfer: ' . $conn->error, 'error', 'saving.php');
            // }
        } else {
            showAlert('Error occurred during record fetching: ' . $conn->error, 'error', 'saving.php');
        }
    } else {
        showAlert('Branch mismatch. Please select the correct branch.', 'error', 'saving.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Management System - Saving Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .dropdown-menu {
            width: 100%;
            text-align: center;
        }
        .dropdown-item {
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="service.php">Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                   
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Saving money</h4>
                    </div>
                    <div class="card-body">
                        <form id="saving" method="POST" action="saving.php">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
                            </div>
                            <br>

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

                            <input type="hidden" name="branch" id="branchInput" value="">
                            <br>
                            <button type="submit" class="btn btn-primary" name="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function selectBranch(branch) {
        document.getElementById('branchInput').value = branch;
        document.getElementById('branchDropdown').innerText = branch;
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
