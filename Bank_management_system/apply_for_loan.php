

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

date_default_timezone_set('Asia/Dhaka');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_time = date('H:i:s');
    echo "Current Time: $current_time<br>"; // Debug statement

    if (isset($_POST['apply'])) {
        // Check if a row with id=1 exists
        $check_sql = "SELECT * FROM time_interval WHERE id = 1";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $row = $check_result->fetch_assoc();
            $stored_start_time = $row['start_time'];
           // echo "Stored Start Time: $stored_start_time<br>"; // Debug statement

            if ($stored_start_time === null) {
                // Insert start_time if it's null
                $sql = "UPDATE time_interval SET start_time = '$current_time' WHERE id = 1";
                $result_msg = "Start time started successfully";
                $alert_icon = 'success';
            } else {
                // Calculate time difference in seconds
                $stored_timestamp = strtotime($stored_start_time);
                $current_timestamp = strtotime($current_time);
                $time_difference = $current_timestamp - $stored_timestamp;
                echo "current time: $time_difference seconds<br>"; // Debug statement

                if ($time_difference > 70 || $time_difference < 0 ) {
                    // Update start_time if difference > 70 seconds
                    $sql = "UPDATE time_interval SET start_time = '$current_time' WHERE id = 1";
                    $result_msg = "Start time started successfully";
                    $alert_icon = 'success';
                } else {
                    // Do nothing if difference <= 70 seconds
                    showAlert('Still has validity', 'info', 'apply_for_loan.php');
                    $sql = null;
                }
            }
        } else {
            // Insert new row if it doesn't exist (shouldn't happen if initially set)
            $sql = "INSERT INTO time_interval (id, start_time) VALUES (1, '$current_time')";
            $result_msg = "Start time started successfully";
            $alert_icon = 'success';
        }

        if (isset($sql) && $conn->query($sql) === TRUE) {
            showAlert($result_msg, $alert_icon, 'apply_for_loan.php');
        } elseif (isset($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['getmoney'])) {
        // Check if a row with id=1 exists
        $check_sql = "SELECT * FROM time_interval WHERE id = 1";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            // Update end_time if row exists
            $row = $check_result->fetch_assoc();
            $stored_start_time = $row['start_time'];
          //  echo "Stored Start Time: $stored_start_time<br>"; // Debug statement

            if ($stored_start_time == NULL) {
                showAlert('You have to set start time first', 'info', 'apply_for_loan.php');
            } else {
                $stored_timestamp = strtotime($stored_start_time);
                $current_timestamp = strtotime($current_time);
                $time_difference = $current_timestamp - $stored_timestamp;
               echo "current time: $time_difference seconds<br>"; // Debug statement

                if ($time_difference > 70 || $time_difference <= 0 ) {
                    showAlert('Validity lost, you have to set start time again', 'info', 'apply_for_loan.php');
                } else if ($time_difference < 40 && $time_difference > 0) {
                    showAlert('You have to wait', 'info', 'apply_for_loan.php');
                } else {
                    $sql = "UPDATE time_interval SET start_time = '$current_time' WHERE id = 1";
                    if ($conn->query($sql) === TRUE) {
                        showAlert('End time updated', 'success', 'get_money.php');
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
        } else {
            // Insert a new row if it doesn't exist (shouldn't happen if initially set)
            $sql = "INSERT INTO time_interval (id, end_time) VALUES (1, '$current_time')";
            if ($conn->query($sql) === TRUE) {
                showAlert('End time updated', 'success', 'apply_for_loan.php');
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Time Interval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
   
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">

              <div class = "card-header">

              <h1 class="text-center mb-4">Apply for Loan</h1>
     
              </div>

                <div class="card-body">
                    <!-- <h5 class="card-title text-center">Choose Action</h5> -->
                    <form method="post" action="apply_for_loan.php">
                        <div class="text-center mb-3">
                            <button type="submit" name="apply" value="apply" class="btn btn-primary mr-2">Apply</button>
                            <button type="submit" name="getmoney" value="getmoney" class="btn btn-danger">Get Money</button>
                        </div>
                    </form>
                </div>

                <div class = "card-footer">

          <h5 class="text-center mb-4"><strong>some rules you have to follow when you apply for money</strong></h5>

                  <ul class="list-group">
                        <?php
                        

                                echo '<li class="list-group-item">
                                    <strong> After apply you have to wait 40s </strong><br>
                                    <strong> after 40s you will get 30s to enter get money option</strong><br>
                                    
                                    <strong> after 40s + 30s = 70s you will lost its validity </strong><br>
                                    <strong>after 70s you have to apply again</strong><br>
                                  </li>';
                       
                        ?>
                    </ul>
            

               </div>

               <div class="card-footer">
         
                <?php

                echo ' <strong> note : After press on apply button within 70s further press on apply will not be consider </strong>';

                ?>


            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
