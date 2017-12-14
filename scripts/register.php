<?php
session_start();

if (!isset($_POST['signup'])) {
	header("location: ../signup.php");
	exit;
}

$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$re_password = $_POST['re-password'];

if ($password != $re_password) {
  $_SESSION['flash'] = array("Passwords don't match", 'danger');
  header('location: ../signup.php');
  exit;
}

require 'database.php';

$query = "INSERT INTO users VALUES('$name', '$username', '$password', 0, 0, 0)";
$result = mysqli_query($conn, $query);

$_SESSION['username'] = $username;
header("location: ../home.php");
exit;
?>
