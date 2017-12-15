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

require 'database.php';

$query = "SELECT username
					FROM users
  				WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
	$_SESSION['flash'] = array("Username taken", 'warning');
	header('location: ../signup.php');
	exit;
}

if ($password != $re_password) {
  $_SESSION['flash'] = array("Passwords don't match", 'warning');
  header('location: ../signup.php');
  exit;
}

$query = "INSERT INTO users VALUES('$name', '$username', '$password', 0, 0, 0)";
$result = mysqli_query($conn, $query);

$_SESSION['username'] = $username;
header("location: ../home.php");
exit;
?>
