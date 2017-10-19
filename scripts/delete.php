<?php
session_start();
if (!isset($_SESSION['username'])) {
        header("location: login.php");
}

$username = $_SESSION['username'];
require 'database.php';

$username = $_SESSION['username'];
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
