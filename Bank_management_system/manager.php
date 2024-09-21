<?php
require 'connectdb.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Transaction History</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- <style>
    body, html {
      height: 100%;
    }
    .center-container {
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style> -->
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

<nav class="navbar navbar-expand-lg navbar-light bg-black">
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

<div class="container-fluid center-container">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-secondary">Manager Info</h5>
                <ul class="list-group">
                    <?php
                    $sql = "SELECT * FROM manager";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $name = $row['name'];
                            $email = $row['email'];
                            $branch = $row['branch'];

                            echo '<li class="list-group-item">
                                <strong>Name:</strong> ' . $name . '<br>
                                <strong>Email:</strong> ' . $email . '<br>
                                <strong>Branch:</strong> ' . $branch . '<br>
                              </li>';
                        }
                    } else {
                        echo '<li class="list-group-item">No manager found.</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>
