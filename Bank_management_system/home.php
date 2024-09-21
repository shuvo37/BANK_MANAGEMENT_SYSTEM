<?php
require 'connectdb.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first');</script>";
    echo "<script>window.location.href = 'log_in.php';</script>";
    exit();
}

$sql = "SELECT * FROM loan";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['account_id'];
        $ending_time = $row['end_date'];

        $endDateTimeObj = new DateTime($ending_time);

        if ($endDateTimeObj < new DateTime()) {
            $deleteQry = "DELETE FROM loan WHERE account_id = '$user_id'";
            $conn->query($deleteQry);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bank Management System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('images/bank2.jpg'); /* Replace with your image */
            background-size: cover;
            background-position: center;
            color: #fff; /* Optional: change text color to white for better visibility */
            height: 100vh; /* Ensure body takes full height */
            margin: 0; /* Remove default margin */
        }
        .navbar {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background for navbar */
        }
        .card {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background for cards */
            border: none; /* Remove card borders */
        }
        .btn {
            width: 100%; /* Make buttons full width */
            margin-bottom: 10px; /* Add space between buttons */
        }
    </style>
</head>
<body>

 
<nav class="navbar navbar-expand-lg navbar-dark bg-black">
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
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="type.png" class="card-img-top" alt="Select Car">
                <div class="card-body text-center">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Transaction Type
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="saving.php">Deposit</a></li>
                            <li><a class="dropdown-item" href="withdrow_money.php">Withdraw</a></li>
                            <li><a class="dropdown-item" href="transfer.php">Transfer</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img src="loan.png" class="card-img-top" alt="Loan">
                <div class="card-body text-center">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            About Loan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="apply_for_loan.php">Get Loan</a></li>
                            <li><a class="dropdown-item" href="payment_loan.php">Payment Loan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img src="type.png" class="card-img-top" alt="Info">
                <div class="card-body text-center">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Info You Need to Know
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="manager.php">Manager Info</a></li>
                            <li><a class="dropdown-item" href="loaninfo.php">Loan Info</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
