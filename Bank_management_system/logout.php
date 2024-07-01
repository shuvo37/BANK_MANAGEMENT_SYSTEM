<?php
session_start();
session_unset();
session_destroy();
echo "<script>alert('You have been logged out');</script>";
echo "<script>window.location.href = 'log_in.php';</script>";
exit();
?>
