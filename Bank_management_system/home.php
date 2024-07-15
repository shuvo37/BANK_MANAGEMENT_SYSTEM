
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

            $deleteQry =  "DELETE FROM loan WHERE account_id = '$user_id'";

            $upd = $conn->query($deleteQry);
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
                            transection type
                            </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="saving.php">Deposite</a></li>
                              <li><a class="dropdown-item" href="withdrow_money.php">withdrow</a></li>
                              <li><a class="dropdown-item" href="transfer.php">Transer</a></li>
                            </ul>
                          </div>


                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="loan.png" class="card-img-top" alt="loan">
                    <div class="card-body text-center">
                    <div class="dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                              About Loan
                            </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="apply_for_loan.php">Get loan</a></li>
                              <li><a class="dropdown-item" href="payment_loan.php">Payment Loan</a></li>
                           
                            </ul>
                          </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="type.png" class="card-img-top" alt="info">
                    <div class="card-body text-center">
                        
                    <div class="dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                              Some info you need to Know
                            </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="manager.php">manager info</a></li>
                              <li><a class="dropdown-item" href="loaninfo.php">Loan info</a></li>
                           
                            </ul>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</body>
</html>