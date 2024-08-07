

<?php
require 'connectdb.php';
session_start();

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

        if ($result->num_rows == 0) {
            showAlert('There is no saving money in your account', 'error', 'withdrow_money.php');
        } else {
            $row = $result->fetch_assoc();
            $total_saving = $row['total_saving'];

            if ($total_saving >= $amount) {
                $updateQry = "UPDATE saving 
                              SET total_saving = total_saving - $amount 
                              WHERE account_id = '$id' AND branch_id = '$branch'";
                $upd = $conn->query($updateQry);

                if ($upd) {
                    showAlert('Account updated successfully', 'success', 'withdrow_money.php');

                    $type = "withdraw";
                $qry = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch,amount)
                        VALUES ('$id', '$type', '$email', '$dob', '$branch ,'$amount')";
                $insrtTransfer = $conn->query($qry);
                } else {
                    showAlert('Error occurred during record update: ' . $conn->error, 'error', 'withdrow_money.php');
                }

                

            } else {
                showAlert('You do not have sufficient amount of money', 'error', 'withdrow_money.php');
            }

           

            
        }
    } else {
        showAlert('Branch mismatch. Please select the correct branch.', 'error', 'withdrow_money.php');
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Management System - Withdraw Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
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
                        <h4>Withdraw Money</h4>
                    </div>
                    <div class="card-body">
                        <form id="withdrow_money" method="POST" action="withdrow_money.php">
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
                            <button type="submit" class="btn btn-primary" name="submit">Withdraw</button>
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