


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
  $trans_id = $_POST['accountId'];
  $branch1 = $_SESSION['user_branch'];
  $id = $_SESSION['user_id'];
  $email = $_SESSION['user_email'];

  date_default_timezone_set('Asia/Dhaka');
  $dob = date('Y-m-d H:i:s');

  $sql = "SELECT * FROM user_info WHERE id = '$trans_id' AND branch = '$branch'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
      $sql1 = "SELECT * FROM saving WHERE account_id = '$id' AND branch_id = '$branch1'";
      $result1 = $conn->query($sql1);

      if ($result1->num_rows == 0) {
          showAlert('There is no saving money in your account', 'error', 'transfer.php');
      } else {
          $row = $result1->fetch_assoc();
          $total_saving = $row['total_saving'];

          if ($total_saving >= $amount) {
              $sql2 = "SELECT * FROM saving WHERE account_id = '$trans_id' AND branch_id = '$branch'";
              $result2 = $conn->query($sql2);

              if ($result2->num_rows == 0) {



                  $qry = "INSERT INTO saving (account_id, total_saving, branch_id) 
                          VALUES ('$trans_id', '$amount', '$branch')";
                  $insrt = $conn->query($qry);

                  $updateQry = "UPDATE saving 
                                SET total_saving = total_saving - $amount 
                                WHERE account_id = '$id' AND branch_id = '$branch1'";
                  $upd = $conn->query($updateQry);

                  if ($upd) {
                      showAlert('Transfer successful', 'success', 'transfer.php');
                      $type = "transfer";
                      $qry = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch,amount)
                              VALUES ('$id', '$type', '$email', '$dob', '$branch ,'$amount')";
                      $insrtTransfer = $conn->query($qry);
                  } else {
                      showAlert('Error occurred during record update: ' . $conn->error, 'error', 'transfer.php');
                  }

                 
              } else {
                  $updateQry = "UPDATE saving 
                                SET total_saving = total_saving + $amount 
                                WHERE account_id = '$trans_id' AND branch_id = '$branch'";
                  $upd = $conn->query($updateQry);

                  $updateQry1 = "UPDATE saving 
                                 SET total_saving = total_saving - $amount 
                                 WHERE account_id = '$id' AND branch_id = '$branch1'";
                  $upd1 = $conn->query($updateQry1);

              

                  if ($upd1) {
                      showAlert('Transfer successful', 'success', 'transfer.php');
                      $type = "transfer";
                $qry = "INSERT INTO transfer (account_id, transfer_type, email, transfer_datetime, branch,amount)
                        VALUES ('$id', '$type', '$email', '$dob', '$branch ,'$amount')";
                $insrtTransfer = $conn->query($qry);
                  } else {
                      showAlert('Error occurred during record update: ' . $conn->error, 'error', 'transfer.php');
                  }
              }


          }
          
          
          else {
              showAlert('You do not have sufficient amount of money', 'error', 'transfer.php');
          }
      }
  } else {
      showAlert('This account does not exist', 'error', 'transfer.php');
  }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Management System - Transfer Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-black">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">BMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="service.php">Service</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        
        
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center"><h4>Transfer Money</h4></div>
          <div class="card-body">
            <form id="transfer" method="POST" action="transfer.php">
              <div class="form-group">
                <label for="accountId">Account ID</label>
                <input type="number" class="form-control" id="accountId" name="accountId" placeholder="Enter Account ID" required>
              </div>
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
              <div class="text-center">
              <button type="submit" class="btn btn-primary" name="submit">Transfer</button>
              </div>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
