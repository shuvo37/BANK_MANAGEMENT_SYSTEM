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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['startDate']) && isset($_POST['endDate'])
     && isset($_POST['amount']) && isset($_POST['submit'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $amount = $_POST['amount'];

        $branch = $_SESSION['user_branch'];
        $id = $_SESSION['user_id'];
        $email = $_SESSION['user_email'];
        date_default_timezone_set('Asia/Dhaka');
        $dob = date('Y-m-d H:i:s');

     
        // Ensure the dates are in the correct format
        $startDateFormatted = date('Y-m-d', strtotime($startDate));
        $endDateFormatted = date('Y-m-d', strtotime($endDate));
        $currentDate = date('Y-m-d');

        // Calculate the difference between start date and end date
        $date1 = new DateTime($startDateFormatted);
        $date2 = new DateTime($endDateFormatted);
        $interval = $date1->diff($date2);
        $monthsDifference = ($interval->y * 12) + $interval->m;

        // Determine interest rate based on loan duration
        $interestRate = 0;

        if ($monthsDifference < 6) {
            showAlert('The difference between start and end date must be at least six months.', 'info', 'get_money.php');
        } 
        
         else {
        
        if ($monthsDifference >= 6 && $monthsDifference < 12) {
            $interestRate = 0.04;
        } elseif ($monthsDifference >= 12 && $monthsDifference < 24) {
            $interestRate = 0.06;
        } elseif ($monthsDifference >= 24 && $monthsDifference < 36) {
            $interestRate = 0.08;
        } elseif ($monthsDifference >= 36) {
            $interestRate = 0.10;
        }

        // Calculate amount with interest and cast to integer
        $amountWithInterest = $amount + ($amount * $interestRate);

        $amountWithInterest = (int) $amountWithInterest;

        $sql = "SELECT * FROM loan WHERE account_id = ? AND branch = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $id, $branch);
        $stmt->execute();
        $result = $stmt->get_result();

        $loan = 0; // Initialize loan variable

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loan = $row['amount'];
        }

        $sql1 = "SELECT * FROM saving WHERE account_id = ? AND branch_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("is", $id, $branch);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $save = 0; // Initialize save variable

        if ($result1->num_rows == 1) {
            $row1 = $result1->fetch_assoc();
            $save = $row1['total_saving'];
        }

        $mul = 2;

        if ($result1->num_rows == 0 || (($mul * $save) < $loan)) {
            showAlert('You have to save money first', 'error', 'get_money.php');
        } elseif ($result->num_rows == 0 || $loan == 0) {
            // Validation
            if ($result->num_rows == 0) {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO loan (account_id, start_date, end_date, amount, branch) 
                VALUES ('$id', '$startDateFormatted', '$endDateFormatted', '$amountWithInterest', '$branch')");
               
                if ($stmt->execute()) {
                    showAlert('New record created successfully', 'success', 'get_money.php');
                    $type = "get loan";
                    $qry = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch, amount)
                            VALUES ( '$id', '$type', '$email', '$dob', '$branch', '$amountWithInterest')";
                    $stmt2 = $conn->prepare($qry);
                 
                    $stmt2->execute();

                } else {
                    showAlert('Error: ' . $stmt->error, 'error', 'get_money.php');
                }
            } else {
                $updateQry = "UPDATE loan SET amount = amount + $amountWithInterest WHERE account_id = '$id' AND branch = '$branch'";
                if ($conn->query($updateQry) === TRUE) {
                    showAlert('Loan updated successfully', 'success', 'get_money.php');
                    $type = "get loan";
                    $qry = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch, amount)
                            VALUES ( '$id', '$type', '$email', '$dob', '$branch', '$amountWithInterest')";
                    $stmt2 = $conn->prepare($qry);
        
                    $stmt2->execute();
                } else {
                    showAlert('Error updating loan details', 'error', 'get_money.php');
                }
            }




        } else {
            showAlert('You have to pay the total loan first then you can get a loan again', 'error', 'get_money.php');
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
    <title>Date Difference Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">BMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                <div class="card-header text-center"><h4>Get your loan</h4></div>
                <div class="card-body">
                    <form action="get_money.php" method="POST">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount"
                                   placeholder="Enter amount" required>
                        </div>
                        <div class="form-group">
                            <label for="startDate">Start Date for loan:</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date for loan:</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                        </div>
                        <button type="submit" id="submit" name="submit" class="btn btn-primary">Get money</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
