<?php
session_start();
if (!isset($_SESSION['username'])) {
        header("location: login.php");
}

$username = $_SESSION['username'];
require 'database.php';

$username = $_SESSION['username'];

$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['cash'] > 0 or $row['dow30_value'] > 0 or $row['overseas_value'] > 0) {
        $_SESSION['display_alert'] = "You need to liquidate portfolio first";
        header("location: ../home.php");
        exit();
}

$query = "DELETE FROM users WHERE username = '$username'";
mysqli_query($conn, $query);
$query = "DELETE FROM user_stocks WHERE username = '$username'";
mysqli_query($conn, $query);
$query = "DELETE FROM transactions WHERE username = '$username'";
mysqli_query($conn, $query);

$_SESSION['logout'] = true;
header("location: logout.php");
exit();
?>
