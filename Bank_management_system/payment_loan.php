

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
    $amount = $_POST['amount'];
    $branch = $_SESSION['user_branch'];
    $id = $_SESSION['user_id'];
    $email = $_SESSION['user_email'];

    date_default_timezone_set('Asia/Dhaka');
    $dob = date('Y-m-d H:i:s');

    // Updated query to reflect the correct column name for branch in saving table if necessary
    $sql = "SELECT * FROM loan WHERE account_id = '$id' AND branch = '$branch'";
    $sql1 = "SELECT * FROM saving WHERE account_id = '$id' AND branch_id = '$branch'"; // Corrected to branch_id

    $result = $conn->query($sql);
    $result1 = $conn->query($sql1);

    if ($result1->num_rows == 1) {
        $row1 = $result1->fetch_assoc();
        $save1 = $row1['total_saving'];
    }

    if ($result1->num_rows == 0) {
        showAlert('You have to first save money', 'info', 'payment_loan.php');
    } else if ($save1 < $amount) {
        showAlert('Saving does not have enough money to pay', 'info', 'payment_loan.php');
    } else if ($result->num_rows == 0) {
        showAlert('You do not have any loan for payment', 'info', 'payment_loan.php');
    } else {
        $row = $result->fetch_assoc();
        $loan = $row['amount'];

        if ($loan == 0) {
            showAlert('You do not have any loan for payment', 'info', 'payment_loan.php');
        } else if ($amount > $loan) {
            showAlert('You are given inappropriate amount of money', 'info', 'payment_loan.php');
        } else {
            $updateQry = "UPDATE loan 
                          SET amount = amount - $amount 
                          WHERE account_id = '$id' AND branch = '$branch'";
            $upd = $conn->query($updateQry);

            $updateQry1 = "UPDATE saving 
                           SET total_saving = total_saving - $amount 
                           WHERE account_id = '$id' AND branch_id = '$branch'";
            $upd1 = $conn->query($updateQry1);

            if ($upd && $upd1) {
                showAlert('Successfully done', 'success', 'payment_loan.php');
                $type = "pay loan";
                $qry = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch,amount)
                        VALUES ('$id', '$type', '$email', '$dob', '$branch','$amount')";
                $insrtTransfer = $conn->query($qry);

                
            } else {
                showAlert('Error occurred during payment loan: ' . $conn->error, 'error', 'payment_loan.php');
            }
        }
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
                        <h4>payment loan</h4>
                    </div>
                    <div class="card-body">
                        <form id="payment_loan" method="POST" action="payment_loan.php">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
                            </div>
                            
                            <br>
                            <button type="submit" class="btn btn-primary" name="submit">ok</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>