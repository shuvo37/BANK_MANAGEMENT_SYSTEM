<?php
require 'connectdb.php';
session_start();

function showAlert($text, $icon, $redirect = null) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
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

if (isset($_POST['saving'])) {
    $id = $_SESSION['user_id'];
    $branch = $_SESSION['user_branch'];

    $sql = "SELECT * FROM saving WHERE account_id = ? AND branch_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $money = "0$";
        showAlert($money, 'info', 'profile.php');
    } else {
        $row = $result->fetch_assoc();
        $total_saving = $row['total_saving'];
        $money = $total_saving . '$';
        showAlert($money, 'info', 'profile.php');
    }
}

if (isset($_POST['loan'])) {
    $id = $_SESSION['user_id'];
    $branch = $_SESSION['user_branch'];

    $sql = "SELECT * FROM loan WHERE account_id = ? AND branch = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $money = "0$";
        showAlert($money, 'info', 'profile.php');
    } else {
        $row = $result->fetch_assoc();
        $total_loan = $row['amount'];
        $money = $total_loan . '$';
        showAlert($money, 'info', 'profile.php');
    }
}

$user_id = $_SESSION['user_id'];

// Fetch the image path from the database if it exists
$sql = "SELECT * FROM images WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Image found, set session variable for profile image
    $row = $result->fetch_assoc();
    $_SESSION['user_img'] = 'Images/' . $row['file'];
} else {
    // No image found, set default image path
    $_SESSION['user_img'] = 'pro.png'; // Adjust with your default image path
}

// Handle image upload
if (isset($_POST['submit'])) {
    $file_name = basename($_FILES['image']['name']);
    $tempname = $_FILES['image']['tmp_name'];
    $folder = 'Images/' . $file_name; // Adjusted folder path
    $directory = 'Images';

    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }

    // Check for file upload errors
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('File upload error: " . $_FILES['image']['error'] . "');</script>";
        exit;
    }

    // Check if user already has an image record
    $sql = "SELECT * FROM images WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result1 = $stmt->get_result();

    if ($result1->num_rows == 0) {
        // Insert new record if none exists
        $query = "INSERT INTO images (user_id, file) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $user_id, $file_name);
        $result = $stmt->execute();

        if ($result) {
            // If insertion successful, move the uploaded file to the folder
            if (move_uploaded_file($tempname, $folder)) {
                $_SESSION['user_img'] = 'Images/' . $file_name; // Update session variable with new image path
                showAlert('File uploaded successfully', 'success', 'profile.php');
            } else {
                echo "<script>alert('Failed to move uploaded file');</script>";
            }
        } else {
            echo "<script>alert('Error inserting file details into database');</script>";
        }
    } else {
        // Update existing record if user already has an image
        $updateQry = "UPDATE images SET file = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updateQry);
        $stmt->bind_param("si", $file_name, $user_id);
        $res = $stmt->execute();

        if ($res) {
            // If update successful, move the uploaded file to the folder
            if (move_uploaded_file($tempname, $folder)) {
                $_SESSION['user_img'] = 'Images/' . $file_name; // Update session variable with new image path
                showAlert('File uploaded successfully', 'success', 'profile.php');
            } else {
                echo "<script>alert('Failed to move uploaded file');</script>";
            }
        } else {
            echo "<script>alert('Error updating file details in database');</script>";
        }
    }
}
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
  <script>
    function validateForm() {
        const fileInput = document.getElementById('fileInput');
        if (!fileInput.value) {
            Swal.fire({
                text: 'Please select an image before uploading.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }
        return true;
    }
  </script>
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

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <!-- Left side content (profile information, etc.) -->
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?php echo $_SESSION['user_img']; ?>" class="rounded-circle mb-3" id="profileImage" alt="Profile Image">
                    <h5 class="card-title"> <?php echo $_SESSION['user_name']; ?></h5>
                    <h5 class="card-title"> <?php echo $_SESSION['user_email']; ?></h5>
                    <h5 class="card-title"> <?php echo 'Account Number: '. $_SESSION['user_id']; ?></h5>
                    <form id="uploadForm" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <input type="file" class="form-control bg-secondary mb-3" id="fileInput" name="image" accept="image/*" onchange="previewImage(event)">
                        <button type="submit" name="submit" class="btn btn-primary">Upload Image</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <form method="post" action="">
                        <button type="submit" id="saving" name="saving" class="btn btn-primary mt-3">Total Saving</button>
                        <button type="submit" id="loan" name="loan" class="btn btn-primary mt-3">Loan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <!-- Right side content (transaction history) -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transaction History</h5>
                    <ul class="list-group">
                        <?php
                        $id = $_SESSION['user_id'];
                        $sql = "SELECT * FROM transfer WHERE account_id = ? ORDER BY transfer_datetime DESC LIMIT 10";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $transaction_datetime = $row['transfer_datetime'];
                                $branch = $row['branch'];
                                $email = $row['email'];
                                $amount = $row['amount'];
                                $transaction_type = $row['transfer_type'];

                                echo '<li class="list-group-item">
                                    <strong>Date_Time:</strong> ' . $transaction_datetime . '<br>
                                    <strong>Branch:</strong> ' . $branch . '<br>
                                    <strong>Email:</strong> ' . $email . '<br>
                                    <strong>Transaction Type:</strong> ' . $transaction_type . '<br>
                                    <strong>Amount:</strong> ' . $amount . '$<br>
                                  </li>';
                            }
                        } else {
                            echo '<li class="list-group-item">No transaction history found.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
