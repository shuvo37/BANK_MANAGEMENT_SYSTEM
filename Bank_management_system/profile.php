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

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">BMS</a>
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

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="pro.png" class="rounded-circle mb-3" id="profileImage" alt="Profile Image">
                        <h5 class="card-title">User Name</h5>
                        <input type="file" class="form-control bg-secondary" id="fileInput" accept="image/*">
                        <button type="button" class="btn btn-primary mt-3">ok</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Transaction History</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Date:</strong> 2023-06-01<br>
                                <strong>Time:</strong> 10:00 AM<br>
                                <strong>Branch:</strong> New York City<br>
                                <strong>Transaction Type:</strong> saving
                            </li>
                            <li class="list-group-item">
                                <strong>Date:</strong> 2023-05-15<br>
                                <strong>Time:</strong> 2:00 PM<br>
                                <strong>Branch:</strong> Los Angeles<br>
                                <strong>Transaction Type:</strong> saving
                            </li>
                         

                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>




</body>
</html>
