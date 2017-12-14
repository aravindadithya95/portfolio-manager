<?php
session_start();

if (!isset($_POST['login'])) {
	header("location: ../login.php");
	exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

require 'database.php';

$query = "SELECT *
					FROM users
  				WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
	$_SESSION['username'] = $username;
	header("location: ../home.php");
} else {
	header("location: ../login.php");
}
?>
