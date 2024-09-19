<?php
include('server/connection.php');
session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_address = $_SESSION['user_address'];
$user_phone = $_SESSION['user_phone'];
$user_photo = $_SESSION['user_photo'];


if (!isset($_SESSION['logged_in'])) {
  header('location: index.php');
  exit;
}

if (isset($_GET['logout'])) {
  if (isset($_SESSION['logged_in'])) {
    unset($_SESSION['logged_in']);
    unset($_SESSION['user_email']);
    header('location: index.php');
    exit;
  }
}
?>

tes 

<a href="userDashboard.php?logout=1" id="logout-btn">
<button type="button" class="btn btn-danger"> Logout</button>