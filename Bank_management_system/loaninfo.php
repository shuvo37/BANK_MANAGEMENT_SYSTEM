<!DOCTYPE html>
<html lang="en">
<head>
  <title>Transaction History</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body, html {
      height: 100%;
    }
    .center-container {
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>
</head>
<body>

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

<div class="container-fluid center-container">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Loan Info</h5>
                <ul class="list-group">
                    <?php
                        echo '<li class="list-group-item">
                            <strong>Interval: 6 Month </strong><br>
                            <strong>Interest rate : 4%</strong><br>
                            
                            <strong>Interval: 1 year </strong><br>
                            <strong>Interest rate : 6%</strong><br>

                            <strong>Interval: 1.5 year </strong><br>
                            <strong>Interest rate : 5%</strong><br>

                            <strong>Interval: 2 year </strong><br>
                            <strong>Interest rate : 8%</strong><br>
                          </li>';
                    ?>
                </ul>
            </div>
            <div class="card-footer">
                <?php
                    echo ' <strong> note : Interval not less than 6 month</strong>';
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
